<?php
require_once __DIR__ . "/config/bootstrap.php";
if($_SERVER["REQUEST_METHOD"]!=="POST")redirect("index.php");
if(!verifyCsrf($_POST["csrf_token"]??null)){flash("error","Token keamanan tidak valid.");redirect("index.php");}
$id=filter_input(INPUT_POST,"id",FILTER_VALIDATE_INT);
if(!$id){flash("error","ID produk tidak valid.");redirect("index.php");}
try{
    $db->beginTransaction();
    $q=$db->prepare("SELECT name FROM products WHERE id=:id FOR UPDATE");$q->execute([":id"=>$id]);$p=$q->fetch();
    if(!$p){$db->rollBack();flash("error","Produk tidak ditemukan.");redirect("index.php");}
    $q=$db->prepare("DELETE FROM products WHERE id=:id");$q->execute([":id"=>$id]);
    $db->commit();flash("success",'Produk "'.$p["name"].'" berhasil dihapus.');
}catch(Throwable $e){
    if($db->inTransaction())$db->rollBack();
    flash("error","Produk gagal dihapus.");
}
redirect("index.php");
