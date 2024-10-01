<?php

$Type = $_POST['Table'];
$index = $_POST['index'];
$buy = $_POST['Buy'];
$Stock = $_POST['Stock'];
$Token = $_POST['Token'];
$Brahch = $_POST['branch'];

$con = new mysqli("localhost", "root" ,"1234", "$Brahch");

function ALL($con)
{
    $query = " select * from Machine, Product where Machine.product = Product.proID;";
    $result = mysqli_query($con, $query);
    
    $products = array(array('proID' => '0', 'name' => '0', 'quantity' => '0', 'price' => 0));

    while($row = mysqli_fetch_assoc($result)) 
    {
        array_push($products, $row);
    }
 
    $jsonResponse = json_encode($products);

    echo $jsonResponse;
}

function oneAtaTime($con, $index)
{
    $query = "select * from Machine where posId = $index;";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    $id = $row['product'];
    $stock = $row['stock'];

    $query = "select * from Product where proID = '$id';";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $data = ['name' => $row["Name"], 'price' => $row["price"], 'img' => $id, 'Quantity' => $row["quantity"], 'Stock' => $stock];

    $jsonResponse = json_encode($data);
    echo $jsonResponse;
}

function Client($con, $index)
{
    $query = "Select * from Client where Token = $index;";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $jsonResponse = json_encode($row);
    echo $jsonResponse;
}

function ClientBuy($con, $buy, $index, $Token)
{
    $query = "Update Client Set ProductId = '$buy', ClientD = $Token where Token = $index";
    $result = mysqli_query($con, $query);
    $data = ['name' => "Name", 'price' => 0, 'img' => 0, 'Quantity' => 0];

    $jsonResponse = json_encode($data);

    echo $jsonResponse;
}

function Admin($con, $index, $Stock)
{
    $query = "Update Machine Set stock = stock + $Stock where PosID = $index";
    $result = mysqli_query($con, $query);   

    $data = ['name' => "Name", 'price' => 0, 'img' => 0, 'Quantity' => 0];

    $jsonResponse = json_encode($data);
    echo $jsonResponse;
}

function ClientConnected($con, $index, $Token)
{
    $query = "Update Client Set ClientD = $Token where Token = $index;";
    $result = mysqli_query($con, $query);

    $jsonResponse = json_encode($result);

    echo $jsonResponse;
}

function QR($con)
{
    $query = "select * from TokenCount;";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['Count'] - 1 ;

    $query = "Select * from Client where Token = $count;";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $jsonResponse = json_encode($row);
    echo $jsonResponse;
}

function Update($con)
{
    $query = "Update TokenCount Set Count = Count + 1;";
    mysqli_query($con, $query);

    $data = ['name' => "Name", 'price' => 0, 'img' => 0, 'Quantity' => 0];

    $jsonResponse = json_encode($data);

    echo $jsonResponse;
}

function Pay($con, $index, $Token, $buy)
{
    $query = "Select * from purchase where PurchaseID = $index AND Token = '$Token';";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    
    if($row) 
    {
        $data = ['Type' => "Already Exist", 'name' => $row["proId"]];
        $jsonResponse = json_encode($data);
        echo $jsonResponse;
        return;
    }
    elseif ($buy != "404")
    {
        $query = "select * from Product where proID = '$buy';";
        mysqli_query($con, $query);
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);

        $query = "Update Machine set stock = stock - 1 where product = '$buy';";
        mysqli_query($con, $query);
        
        $Name = $row["Name"];
        $Price = $row["price"];
        
        $query = "Insert Into purchase(PurchaseID, Token, ProductName, Price, proId) values($index, $Token, '$Name', $Price, '$buy');";
        $result = mysqli_query($con, $query);

        $data = ['Type' => "Success"];
        $jsonResponse = json_encode($data);
        echo $jsonResponse;
        return;
    }

    $data = ['Type' => "null"];
    $jsonResponse = json_encode($data);
    echo $jsonResponse;
}

function BuycheckBABY($con, $index, $Token)
{
    $query = "Select * from purchase where PurchaseID = $index AND Token = '$Token';";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    if($row) 
    {
        $data = ['Type' => "Yep", 'name' => $row["proId"]];
        $jsonResponse = json_encode($data);
        echo $jsonResponse;
    }
    else 
    {
        $data = ['Type' => "Nope"];
        $jsonResponse = json_encode($data);
        echo $jsonResponse;
    }
}

function PriceCheck($con, $buy) 
{
    
    $query = "select * from product where proID = '$buy'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    $jsonResponse = json_encode($row);
    echo $jsonResponse;
}

if ($Type == 'ALL') 
{
    ALL($con);
}
elseif ($Type == "Machine")
{
    oneAtaTime($con, $index);
}
elseif ($Type == "Client") 
{
    Client($con, $index);
}
elseif ($Type == "ClientBuy") 
{
    ClientBuy($con, $buy, $index, $Token);
}
elseif ($Type == "Admin") 
{
    Admin($con, $index, $Stock);
}
elseif ($Type == "ClientConnected") 
{
    ClientConnected($con, $index, $Token);
}
elseif ($Type == "QR") 
{
    QR($con);
}
elseif ($Type == "Update") 
{
    Update($con);
}
elseif ($Type == "Pay") 
{
    Pay($con, $index, $Token, $buy);
}
elseif ($Type == "BuycheckBABY")
{
    BuycheckBABY($con, $index, $Token);
}
elseif ($Type == "priceCheck") 
{
    PriceCheck($con, $buy);
}

$con->close();
?>