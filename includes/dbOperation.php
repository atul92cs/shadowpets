<?php
  class dbOperation
  {
     private $con;
    function __construct()
	{
	  require_once dirname(__FILE__).'/dbconnect.php';
	  $db=new Dbconnnect();
	  $this->con=$db->connect();
	}
	function registerUser($name,$email,$phone,$password)
	{
		if(!$this->isUserExist($email))
		{
		$pass=md5($password);
		$stmt=$this->con->prepare("INSERT INTO users(Name,Email,Phone,Password)VALUES(?,?,?,?)");
		$stmt->bind_param("ssss",$name,$email,$phone,$pass);
		if($stmt->execute())
			return USER_CREATED;
		
		return USER_CREATION_FAILED;
		}
		return USER_EXIST;
	}
	
	function userLogin($phone,$password)
	{
		$pass=md5($password);
		$stmt=$this->con->prepare("SELECT * FROM users WHERE Phone=? and Password=?");
		$stmt->bind_param("ss",$phone,$pass);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows>0;
		
	}
	function userUpdate($id,$name,$email,$phone,$password)
	{
		$pass=md5($password);
		$stmt=$this->con->prepare("UPDATE users SET Name=?,Email=?,Phone=?,Password=? WHERE id=?");
		$stmt->bind_param($name,$email,$phone,$pass,$id);
		if($stmt->execute())
			return true;
		return false;
	}
	function isUserExist($email)
	{
		$stmt=$this->con->prepare("SELECT * FROM users WHERE Email=?");
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows>0;
	}
	function getUserByPhone($phone)
	{
		$stmt=$this->con->prepare("SELECT user_id,Name,Phone,Email FROM users WHERE Phone=?");
		$stmt->bind_param("s",$phone);
		$stmt->execute();
		$stmt->bind_result($id,$name,$phone,$email);
		$user=array();
		$stmt->fetch();
		$user['Id']=$id;
		$user['Name']=$name;
		$user['Phone']=$phone;
		$user['Email']=$email;
		return $user;
		
	}
	function getProducts()
	{
		$stmt=$this->con->prepare("SELECT * FROM products");
		$stmt->execute();
		$stmt->bind_result($id,$name,$image,$cost,$sellerid);
		$product=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['productId']=$id;
			$temp['productName']=$name;
			$temp['productImage']=$image;
			$temp['productCost']=$cost;
			$temp['sellerId']=$sellerid;
			array_push($product,$temp);
		}
		return $product;
	}
	function getVets()
	{
		$stmt=$this->con->prepare("SELECT vet_id,vet_name,vet_address,vet_city,vet_email FROM vets");
		$stmt->execute();
		$stmt->bind_result($id,$name,$address,$city,$email);
		$vet=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['vetId']=$id;
			$temp['vetName']=$name;
			$temp['vetAddress']=$address;
			$temp['vetCity']=$city;
			$temp['vetEmail']=$email;
			array_push($vet,$temp);
		}
		return $vet;
	}
	function getTrainers()
	{
		$stmt=$this->con->prepare("SELECT trainer_id,trainer_name,trainer_address FROM trainers");
		$stmt->execute();
		$stmt->bind_result($id,$name,$address);
		$trainer=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['trainderId']=$id;
			$temp['trainderName']=$name;
			$temp['trainderaAddress']=$address;
			array_push($trainer,$temp);
		}
		return $trainer;
	}
	function getPets()
	{
		$stmt=$this->con->prepare("SELECT pet_id,pet_name,pet_image,pet_cost,seller_id FROM pets");
		$stmt->execute();
		$stmt->bind_result($id,$name,$image,$cost,$seller);
		$pet=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['petId']=$id;
			$temp['petName']=$name;
			$temp['petImage']=$image;
			$temp['petCost']=$cost;
			$temp['sellerId']=$seller;
			array_push($pet,$temp);
		}
		return $pet;
	}
	function getDaycare()
	{
		$stmt=$this->con->prepare("SELECT * FROM daycare");
		$stmt->execute();
		$stmt->bind_result($id,$name,$address,$location);
		$daycare=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['daycareId']=$id;
			$temp['daycareName']=$name;
			$temp['dayacreAddress']=$address;
			$temp['daycareLocation']=$location;
			array_push($daycare,$temp);
		}
		return $daycare;
	}
	function getSpa()
	{
		$stmt=$this->con->prepare("SELECT spa_id,spa_name,spa_address,spa_location FROM spa");
		$stmt->execute();
		$stmt->bind_result($id,$name,$address,$location);
		$spa=array();
		while($stmt->fetch())
		{
			$temp=array();
			$temp['spaId']=$id;
			$temp['spaName']=$name;
			$temp['spaAddress']=$address;
			$temp['spaLocation']=$location;
		}
	}
	function enquireDaycare($name,$contact,$id,$date)
	{
		$stmt=$this->con->prepare("INSERT INTO daycare_appointmnet(user_name,user_contact,daycare_id,booking_date)VALUES(?,?,?,?)");
		$stmt->bind_param($name,$contact,$id,$date);
		if($stmt->execute())
			return true;
		return false;
		
	}
	function enquirePet($title,$message,$name,$contact,$sellerid)
	{
		$stmt=$this->con->prepare("INSERT INTO pet_enquiry(enquiry_title,enquiry_message,customer_contact,seller_id,user_name)VALUES (?,?,?,?,?)");
		$stmt->bind_param($title,$message,$contact,$sellerid,$name);
		if($stmt->execute())
			return true;
		return false;
	}
	function enquireTrainer($trainerid,$name,$contact,$message)
	{
		$stmt=$this->con->prepare("INSERT INTO pet_enquiry(trainer_id,user_name,user_contact,user_message)VALUES(?,?,?,?)");
		$stmt->bind_param($trainderId,$name,$contact,$message);
		if($stmt->execute())
		    return true;
		return false;
	}
	function bookVet($vetId,$contact,$name,$date)
	{
		$stmt=$this->con->prepare("INSERT INTO vet_appointment(vet_id,user_contact,user_name,booking_date)VALUES(?,?,?,?)");
		$stmt->bind_param($vetId,$contact,$name,$date);
		if($stmt->execute())
			return true;
		return false;
		
	}
	function bookSpa($name,$contact,$spaId,$date)
	{
		$stmt=$this->con->prepare("INSERT INTO spa_appointment(spa_id,user_name,user_contact,booking_date)VALUES(?,?,?,?)");
		$stmt->bind_param($spaId,$name,$contact,$date);
		if($stmt->execute())
		  return true;
		return false;
	}
	function placeOrder($name,$contact,$cost,$contact,$address)
	{
		$order_date=date("d-m-Y");
		$status="placed";
		$stmt=$this->con->prepare("INSERT INTO order (order_cost,order_address,order_date,user_name,user_contact,order_status)VALUES(?,?,?,?,?,?)");
		$stmt->bind_param($cost,$address,$order_date,$name,$contact,$status);
		if($stmt->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
  }
?>