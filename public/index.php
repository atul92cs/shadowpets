<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require_once '../includes/dbOperation.php';
$app= new \Slim\App(['settings'=>['displayErrorDetails'=>true]]);
$app->get('/test',function(Request $req,Response $res){
	    $result="it works.app is updated";
		$res->getBody()->write(json_encode(array($result)));
});
$app->post('/register',function(Request $req,Response $res)
{
	if(isTheseParametersAvailable(array('name','email','phone','password')))
	{
		$requestedData=$req->getParsedBody();
		
		$name=$requestedData['name'];
		$email=$requestedData['email'];
		$phone=$requestedData['phone'];
		$password=$requestedData['password'];
		$db = new dbOperation();
		$responseData=array();
		
		$result=$db->registeruser($name,$email,$phone,$password);
		
		if($result==USER_CREATED)
		{
			$responseData['error']=false;
			$responseData['Message']='User Registered Succesfully';
			$reponseData['User']=$db->getUserByPhone($phone);
		}
		else if($result==USER_CREATION_FAILED)
		{
			$responseData['error']=true;
			$responseData['Message']='Error: User Creation failed, please try again';
			
		}
		else if($result==USER_EXISTS)
		{
			$responseData['error']=true;
			$responseData['Message']='Error:user already exists';
		}
		$res->getBody()->write(json_encode($responseData));
	}
});
 $app->post('/login',function(Request $req,Response $res)
 {
	 if(isTheseParametersAvailable(array('phone','password')))
	 {
		 $requestedData=$req->getParsedBody();
		 $phone=$requestedData['phone'];
		 $password=$requestedData['password'];
		 $db=new dbOperation();
		 $responseData=array();
		 
		 $result=$db->userLogin($phone,$password);
		 if($result==true)
		 {
			 $responseData['error']=false;
			 $responseData['user']=$db->getUserByPhone($phone);
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error:Please try again';
		 }
	      $res->getBody()->write(json_encode($responseData));	 
	 }
 });
 $app->post('/bookVet',function(Request $req,Response $res){
	 if(isTheseParametersAvailable(array('vetId','vetName','name','contact','date')))
	 {
		 $requestedData=$req->getParsedBody();
		 $vetid=$requestedData['vetId'];
		 $vetName=$requestedData['vetName'];
		
		 $name=$requestedData['name'];
		  $contact=$requestedData['contact'];
		 $date=$requestedData['date'];
		 $db=new dbOperation();
		 $responseData=array();
		 $result=$db->bookVet($vetid,$vetName,$name,$contact,$date);
		 if($result==true)
		 {
			 $responseData['error']=false;
			 $reponseData['Message']='Appointment book succesfully';
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error 500 please try again later';
		 }
		 $res->getBody()->write(json_encode($responseData));
	 }
 });
 $app->post('/bookSpa',function(Request $req,Response $res){
	 if(isTheseParametersAvailable(array('spaId','name','contact','date')))
	 {
		 $requestedData=$req->getParsedBody();
		  $spaid=$requestedData['spaId'];
		 $name=$requestedData['name'];
		 $contact=$requestedData['contact'];
		 $date=$requestedData['date'];
		 $db=new dbOperation();
		 $responseData=array();
		 $result=$db->bookSpa($spaid,$name,$contact,$date);
		 if($result==true)
		 {
			 $responseData['error']=false;
			 $responseData['Message']='Appointment book succesfully';
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error 500 please try again';
		 }
		 $res->getBody()->write(json_encode($responseData));
	 }
	 
 });
 $app->post('/enquireDaycare',function(Request $req,Response $res){
	   if(isTheseParametersAvailable(array('name','contact','daycareId','date')))
	   {
		   $requestedData=$req->getParsedBody();
		   $name=$requestedData['name'];
		   $contact=$requestedData['contact'];
		   $daycareid=$requestedData['daycareId'];
		   $date=$requestedData['date'];
		   $db=new dbOperation();
		   $responseData=array();
		   $result=$db->enquireDaycare($name,$contact,$daycareid,$date);
		   if($result==true)
		   {
			   $responseData['error']=false;
			   $responseData['Message']='Enquiry sent succesfully';
		   }
		   else
		   {
			   $responseData['error']=true;
			   $responseData['Message']='Error : please try again';
		   }
		   $res->getBody()->write(json_encode($responseData));
	   }
 });
 $app->post('/enquirePet',function(Request $req,Response $res){
	 if(isTheseParametersAvailable(array('title','message','contact','name','sellerId')))
	 {
		 $requestedData=$req->getParsedBody();
		 $title=$requestedData['title'];
		 $message=$requestedData['message']; 
		 $contact=$requestedData['contact'];
		 $name=$requestedData['name'];
		 $sellerid=$requestedData['sellerId'];
		 $db = new dbOperation();
		 $responseData=array();
		 $result=$db->enquirePet($title,$message,$contact,$name,$sellerid);
		 if($result==true)
		 {
			 $responseData['error']=false;
			 $responseData['Message']='booking done succesfully';
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error:please try again';
		 }
		 $res->getBody()->write(json_encode($responseData));
	 }
 });
 $app->post('/enquireTrainer',function(Request $req,Response $res){
	 if(isTheseParametersAvailable(array('name','contact','trainerId','message')))
	 {
		 $requestedData=$req->getParsedBody();
		
		 $name=$requestedData['name'];
		 $contact=$requestedData['contact'];
		 $contact=$requestedData['contact'];
		  $trainerid=$requestedData['trainerId'];
		 $message=$requestedData['message'];
		 $db=new dbOperation();
		 $responseData=array();
		 $result=$db->enquireTrainer($name,$contact,$trainerid,$message);
		 if($result=true)
		 {
			 $responseData['error']=false;
			 $responseData['Message']='Booking done sucessfully';
		 }
		 else
		 {
			 $responseData['error']=true;
			 $responseData['Message']='Error:please try again';
		 }
		 $res->getBody()->write(json_encode($responseData));
	 }
	 
 });
 $app->get('/dayCareOrder/{contact}',function(Request $req,Response $res){
	       $contact =$req->getAttribute('contact');
		   $db=new dbOperation();
		   $order_id=$db->getDcOrder($contact);
		   $res->getBody()->write(json_encode($order_id));
 });
 $app->get('/petEnqOrder/{contact}',function(Request $req,Response $res){
	 $contact=$req->getAttribute('contact');
	 $db=new dbOperation();
	 $order_id=$db->getPeorder($contact);
	 $res->getBody()->write(json_encode($order_id));
 });
 $app->get('/spaOrder/{contact}',function(Request $req,Response $res){
	 $contact=$req->getAttribute('contact');
	 $db=new dbOperation();
	 $order_id=$db->getSaorder($contact);
	 $res->getBody()->write(json_encode($order_id));
 });
 $app->get('/trainEqOrder/{contact}',function(Request $req,Response $res){
	 $contact=$req->getAttribute('contact');
	 $db=new dbOperation();
	 $order_id=$db->getteOrder($contact);
	 $res->getBody()->write(json_encode($order_id));
 });
 $app->get('/vetBkOrder/{contact}',function(Request $req,Response $res){
	 $contact=$req->getAttribute('contact');
	 $db=new dbOperation();
	 $order_id=$db->getvbOrder($contact);
	 $res->getBody()->write(json_encode($order_id));
 });
 $app->get('/products',function(Request $req,Response $res){
	 $db=new dbOperation();
	 $products=$db->getProducts();
	 $res->getBody()->write(json_encode(array("Products"=>$products)));
	 
 });
 $app->get('/pets',function(Request $req,Response $res){
	 $db= new dbOperation();
	 $pets=$db->getPets();
	 $res->getBody()->write(json_encode(array("Pets"=>$pets)));
 });
 $app->get('/spas',function(Request $req,Response $res){
	 $db = new dbOperation();
	 $spas=$db->getSpa();
	 $res->getBody()->write(json_encode(array("Spa"=>$spas)));
 });
 $app->get('/daycare',function(Request $req,Response $res){
	 $db = new dbOperation();
	 $daycares=$db->getDaycare();
	 $res->getBody()->write(json_encode(array("DayCare"=>$daycares)));
	 
 });
 $app->get('/trainer',function(Request $req,Response $res){
	 $db= new dbOperation();
	 $trainers=$db->getTrainers();
	 $res->getBody()->write(json_encode(array("Trainer"=>$trainers)));
 });
 $app->get('/vet',function(Request $req,Response $res){
	 $db=new dbOperation();
	 $vets=$db->getVets();
	 $res->getBody()->write(json_encode(array("Vets"=>$vets)));
 });
 function isTheseParametersAvailable($required_fields)
 {
	  $error=false;
	  $error_fields="";
	  $request_params=$_REQUEST;
	  foreach($required_fields as $field)
	  {
		  if(!isset($request_params[$field])||strlen(trim($request_params[$field]))<=0)
		  {
			  $error=true;
			  $error_fields=$field.',';
			  
		  }
	  }
	  if($error)
	  {
		  $response=array();
		  $response["error"]=true;
		  $response["message"]='Required Field(s)'.substr($error_fields,0,-2).'is missing or empty';
		  echo json_encode($response);
		  return false;
	  }
	  return true;
 }
   $app->run();
?>
