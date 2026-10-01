<?php
require_once __DIR__ . "/config/bootstrap.php";
$search=clean($_GET["search"]??"");$where="";$params=[];
if($search!==""){$where="WHERE p.name LIKE :search OR p.sku LIKE :search OR c.name LIKE :search OR s.name LIKE :search";$params[":search"]="%".$search."%";}
$q=$db->prepare("SELECT p.name,p.sku,c.name category_name,s.name supplier_name,p.quantity,p.price,p.description,p.created_at FROM products p INNER JOIN categories c ON c.id=p.category_id INNER JOIN suppliers s ON s.id=p.supplier_id {$where} ORDER BY p.id DESC");
$q->execute($params);
header("Content-Type:text/csv;charset=utf-8");header("Content-Disposition:attachment;filename=laporan-inventaris.csv");
$out=fopen("php://output","w");fputcsv($out,["Nama Produk","SKU","Kategori","Supplier","Stok","Harga","Deskripsi","Tanggal"]);
while($r=$q->fetch())fputcsv($out,[$r["name"],$r["sku"],$r["category_name"],$r["supplier_name"],$r["quantity"],$r["price"],$r["description"],$r["created_at"]]);
fclose($out);exit;
