<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/customers.php";
require_once __DIR__ . "/../fields/productTypes.php";
require_once __DIR__ . "/../fields/generic/boolean.php";
require_once __DIR__ . "/../fields/generic/textbox.php";

class ProductsGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Products",$constraint);
		$this->fields["owner"]      = new CustomersField();
		$this->fields["display"]    = new TextBox(); 
		$this->fields["display"]->style = "width: 15px;"; 
		$this->fields["id"]         = new LinkButton("/tables/product.php?id={id}&type={type}");
		$this->fields["label"]      = new TextBox(); 
		$this->fields["info_uri"]   = new LinkButton("{info_uri}"); 
		$this->fields["info_uri"]->label = "info";
		$this->fields["info_uri"]->target = "_blank";
		$this->fields["recap_uri"]  = new LinkButton("{recap_uri}");
		$this->fields["recap_uri"]->javascript->onClick = "window.open(this.href,'recap_uri','status=0,toolbar=0,location=0,menubar=0,directories=0,width=200,height=200'); return false;"; 
		$this->fields["recap_uri"]->label = "specs";
		$this->fields["type"]       = new ProductTypesField(); 
		$this->fields["enabled"]    = new BooleanField(); 
		$this->fields["recurring"]  = new BooleanField(); 
		$this->fields["price_1"]    = new TextBox(); 
		$this->fields["price_3"]    = new TextBox(); 
		$this->fields["price_6"]    = new TextBox(); 
		$this->fields["price_12"]   = new TextBox(); 
		$this->fields["price_1"]->javascript->onChange = "this.form.elements['price_3' ].value=Math.round(this.value     ); 
								  this.form.elements['price_6' ].value=Math.round(this.value*0.95);
								  this.form.elements['price_12'].value=Math.round(this.value*0.9 );";
		$this->fields["price_3"]->javascript->onChange = "this.form.elements['price_6' ].value=Math.round(this.value*0.95);
								  this.form.elements['price_12'].value=Math.round(this.value*0.9 );";
		$this->fields["price_6"]->javascript->onChange = "this.form.elements['price_12'].value=Math.round(this.value*0.9 );";

        }
}
