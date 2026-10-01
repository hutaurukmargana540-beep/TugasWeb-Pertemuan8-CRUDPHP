<?php
require_once __DIR__ . "/config/bootstrap.php";
$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);
if(!$id){flash("error","ID produk tidak valid.");redirect("index.php");}
$categories=$db->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$suppliers=$db->query("SELECT id,name FROM suppliers ORDER BY name")->fetchAll();
$q=$db->prepare("SELECT * FROM products WHERE id=:id"); $q->execute([":id"=>$id]); $product=$q->fetch();
if(!$product){flash("error","Produk tidak ditemukan.");redirect("index.php");}
$errors=[];
if($_SERVER["REQUEST_METHOD"]==="POST"){
    if(!verifyCsrf($_POST["csrf_token"]??null)) $errors[]="Token keamanan tidak valid.";
    $name=clean($_POST["name"]??""); $sku=clean($_POST["sku"]??""); $categoryId=clean($_POST["category_id"]??""); $supplierId=clean($_POST["supplier_id"]??""); $quantity=clean($_POST["quantity"]??""); $price=clean($_POST["price"]??""); $description=clean($_POST["description"]??"");
    if($name==="")$errors[]="Nama produk wajib diisi."; if($sku==="")$errors[]="SKU wajib diisi."; if(!ctype_digit($categoryId))$errors[]="Kategori wajib dipilih."; if(!ctype_digit($supplierId))$errors[]="Supplier wajib dipilih."; if($quantity===""||!ctype_digit($quantity))$errors[]="Stok harus angka 0 atau lebih."; if($price===""||!is_numeric($price)||(float)$price<0)$errors[]="Harga harus angka 0 atau lebih.";
    if(!$errors){
        try{
            $q=$db->prepare("UPDATE products SET category_id=:category_id,supplier_id=:supplier_id,name=:name,sku=:sku,quantity=:quantity,price=:price,description=:description WHERE id=:id");
            $q->execute([":category_id"=>(int)$categoryId,":supplier_id"=>(int)$supplierId,":name"=>$name,":sku"=>$sku,":quantity"=>(int)$quantity,":price"=>(float)$price,":description"=>$description,":id"=>$id]);
            flash("success","Produk berhasil diperbarui.");redirect("index.php");
        }catch(PDOException $e){$errors[]=$e->getCode()==="23000"?"SKU sudah digunakan produk lain.":"Produk gagal diperbarui.";}
    }
    $product=array_merge($product,["name"=>$name,"sku"=>$sku,"category_id"=>$categoryId,"supplier_id"=>$supplierId,"quantity"=>$quantity,"price"=>$price,"description"=>$description]);
}
$pageTitle="Edit Produk";require __DIR__."/includes/header.php";
?>
<section class="form-page"><div class="form-card">
<div class="eyebrow">✎ UPDATE</div><h1>Edit <span>Produk</span></h1><p class="muted">Form sudah terisi dari data yang dipilih.</p>
<?php if($errors): ?><div class="alert error"><?php foreach($errors as $x): ?><div>• <?=e($x)?></div><?php endforeach;?></div><?php endif;?>
<form method="post" class="product-form"><input type="hidden" name="csrf_token" value="<?=e(csrfToken())?>">
<div class="form-grid">
<div class="field full"><label>Nama Produk</label><input name="name" value="<?=e($product["name"])?>" required></div>
<div class="field"><label>SKU</label><input name="sku" value="<?=e($product["sku"])?>" required></div>
<div class="field"><label>Kategori</label><select name="category_id"><?php foreach($categories as $c): ?><option value="<?=$c["id"]?>" <?=$product["category_id"]==$c["id"]?"selected":""?>><?=e($c["name"])?></option><?php endforeach;?></select></div>
<div class="field"><label>Supplier</label><select name="supplier_id"><?php foreach($suppliers as $s): ?><option value="<?=$s["id"]?>" <?=$product["supplier_id"]==$s["id"]?"selected":""?>><?=e($s["name"])?></option><?php endforeach;?></select></div>
<div class="field"><label>Stok</label><input type="number" min="0" name="quantity" value="<?=e((string)$product["quantity"])?>" required></div>
<div class="field"><label>Harga (Rp)</label><input type="number" min="0" step="0.01" name="price" value="<?=e((string)$product["price"])?>" required></div>
<div class="field full"><label>Deskripsi</label><textarea name="description" rows="4"><?=e($product["description"]??"")?></textarea></div>
</div>
<div class="form-actions"><a class="btn ghost" href="index.php">Batal</a><button class="btn primary">Update Produk →</button></div>
</form></div></section>
<?php require __DIR__."/includes/footer.php"; ?>
