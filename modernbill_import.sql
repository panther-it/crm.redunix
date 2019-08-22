-- modernbill.client_info.client_status
-- 1 = niet actief
-- 2 = actief
-- 3 = in behandeling
-- 4 = geannuleerd
-- 5 = fraude

-- crm.customers.status
-- 1=Onbekend
-- 2=Actief
-- 3=Blocked
-- 4=Geannuleerd
-- 5=Nieuw

--  == UPDATE OLD CUSTOMER ==
UPDATE crm.customers c
              ,  modernbill.client_info mb
        SET  c.state = CASE mb.client_status
                    WHEN 1 then 4
                    WHEN 2 then 2
                    WHEN 3 then 5
                    WHEN 4 then 4
                    WHEN 5 then 4
                END 
WHERE c.name = IF(mb.client_company='',  CONCAT(mb.client_fname,' ',mb.client_lname), mb.client_company);

--  == INSERT NEW CUSTOMERS ==
INSERT INTO crm.customers(name,state)
SELECT IF(mb.client_company='',  CONCAT(mb.client_fname,' ',mb.client_lname), mb.client_company) AS name
              , CASE mb.client_status
                    WHEN 1 then 4
                    WHEN 2 then 2
                    WHEN 3 then 5
                    WHEN 4 then 4
                    WHEN 5 then 4
                END              AS state
FROM modernbill.client_info mb
WHERE IF(mb.client_company='',  CONCAT(mb.client_fname,' ',mb.client_lname), mb.client_company) NOT IN 
(SELECT name FROM crm.customers);

--  == UPDATE OLD ORGANIZATIONS ==
UPDATE crm.organizations o
              ,  modernbill.client_info mb
        SET o.name = IF(mb.client_company='',  CONCAT(mb.client_fname,' ',mb.client_lname), mb.client_company)
              , o.street = mb.client_address
              , o.zipcode = mb.client_zip
              , o.city = mb.client_city
              , o.fax = mb.client_phone2
              , o.country = mb.client_country
WHERE o.email = mb.client_email;

--  == INSERT NEW ORGANIZATIONS ==
INSERT INTO crm.organizations
(name,street,zipcode,city,email,phone,fax,country)
SELECT 
    IF(mb.client_company='',  CONCAT(mb.client_fname,' ',mb.client_lname), mb.client_company) AS name
,   mb.client_address
,   mb.client_zip
,   mb.client_city
,   mb.client_email
,   mb.client_phone1
,   mb.client_phone2
,   mb.client_country
FROM modernbill.client_info mb
WHERE mb.client_email NOT IN (SELECT email FROM crm.organizations);

UPDATE crm.organizations o
              , crm.customers c
SET o.owner = c.id
      ,  c.organization = o.id
WHERE o.name = c.name;

--  == UPDATE OLD CONTACTS ==
UPDATE crm.contacts c
              ,  modernbill.client_info mb
        SET c.firstname = mb.client_fname
              , c.lastname = mb.client_lname
              , c.phone = mb.client_phone1
              , c.fax = mb.client_phone2
              , c.language = mb.client_country
WHERE c.email = mb.client_email;

--  == INSERT NEW CONTACTS ==
INSERT INTO crm.contacts
(firstname,lastname,email,phone,phone_mobile,fax,language,username,password)
SELECT 
   mb.client_fname
,   mb.client_lname
,   mb.client_email
,   mb.client_phone1
,   mb.client_phone1
,   mb.client_phone2
,   mb.client_country
,   mb.client_username
,   mb.client_real_pass
FROM modernbill.client_info mb
WHERE mb.client_email NOT IN (SELECT email FROM crm.contacts);

UPDATE crm.contacts c
              , crm.organizations o
SET c.owner = o.owner
      ,  c.organization = o.id
WHERE o.email = c.email;

--  == UPDATE OLD ORDERS ==
-- mb.cp.cp_status
-- 2 = active
-- 3 = nieuw
-- 1 = schorsen
-- 4 = geannuleerd
-- 5 = fraude
-- 6 = fout
-- 7 = pending renewal

UPDATE crm.orders o
              , modernbill.client_package cp
              , crm.customers c
              , crm.products p
              , modernbill.package_type mbpt
              , modernbill.client_info mbci
        SET o.enabled = CASE cp.cp_status
                                           WHEN 2 THEN 1
                                           WHEN 3 THEN 1
                                           ELSE 0
                                    END
              , o.price = IF(cp.pack_price>0.01, cp.pack_price, NULL)
              , o.date_start = FROM_UNIXTIME(cp.cp_start_stamp)
              , o.date_end   = CASE cp.cp_status
                                           WHEN 2 THEN NULL 
                                           WHEN 3 THEN NULL
                                           ELSE now()
                               END
              , o.recurring  = CASE cp.cp_billing_cycle
                                           WHEN 1 THEN 'M'
                                           WHEN 3 THEN 'K'
                                           WHEN 6 THEN 'H'
                                           WHEN 12 THEN 'J'
                                           WHEN 111 THEN 'J'
                                        END
WHERE     o.customer = c.id
AND o.product = p.id
AND p.label = mbpt.pack_name
AND mbpt.pack_id = cp.pack_id
AND c.name = IF(mbci.client_company='',  CONCAT(mbci.client_fname,' ',mbci.client_lname), mbci.client_company)
AND mbci.client_id = cp.client_id;


--  == INSERT NEW ORDERS ==
INSERT IGNORE INTO crm.orders(customer,product,label,enabled,price,date_start,recurring)
SELECT c.id
              , p.id
              , IFNULL(mbd.domain_name, mbcp.domain)
              , CASE mbcp.cp_status
                                           WHEN 2 THEN 1
                                           WHEN 3 THEN 1
                                           ELSE 0
                                    END AS enabled
              , IF(mbcp.pack_price>0.01, mbcp.pack_price, NULL)
              , FROM_UNIXTIME(mbcp.cp_start_stamp)
              , CASE mbcp.cp_billing_cycle
                                           WHEN   1 THEN 'M'
                                           WHEN   3 THEN 'K'
                                           WHEN   6 THEN 'H'
                                           WHEN  12 THEN 'J'
                                           WHEN 111 THEN 'J'
                                        END AS recurring
FROM crm.customers                      c
         , modernbill.client_info       mbci
         , crm.products                 p
         , modernbill.package_type      mbpt
         , modernbill.client_package    mbcp
 LEFT JOIN  modernbill.account_details  mba
        ON  mba.cp_id      = mbcp.cp_id
 LEFT JOIN  modernbill.domain_names     mbd
        ON  mba.domain_id  = mbd.domain_id
     WHERE c.name          = IF(mbci.client_company='',  CONCAT(mbci.client_fname,' ',mbci.client_lname), mbci.client_company)
       AND mbci.client_id  = mbcp.client_id
       AND p.label         = mbpt.pack_name
       AND mbcp.pack_id    = mbpt.pack_id;

