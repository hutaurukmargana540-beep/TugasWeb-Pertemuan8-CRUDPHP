<?php
require_once __DIR__ . "/config/bootstrap.php";
$categories=$db->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$suppliers=$db->query("SELECT id,name FROM suppliers ORDER BY name")->fetchAll();
$errors=[];
$old=["name"=>"","sku"=>"","category_id"=>"","supplier_id"=>"","quantity"=>"0","price"=>"0","description"=>""];

if($_SERVER["REQUEST_METHOD"]==="POST"){
    if(!verifyCsrf($_POST["csrf_token"]??null)) $errors[]="Token keamanan tidak valid.";
    foreach($old as $k=>$_) $old[$k]=clean($_POST[$k]??"");
    if($old["name"]==="") $errors[]="Nama produk wajib diisi.";
    if($old["sku"]==="") $errors[]="SKU wajib diisi.";
    if(!ctype_digit($old["category_id"])) $errors[]="Kategori wajib dipilih.";
    if(!ctype_digit($old["supplier_id"])) $errors[]="Supplier wajib dipilih.";
    if($old["quantity"]==="" || !ctype_digit($old["quantity"])) $errors[]="Stok harus angka 0 atau lebih.";
    if($old["price"]==="" || !is_numeric($old["price"]) || (float)$old["price"]<0) $errors[]="Harga harus angka 0 atau lebih.";
    if(!$errors){
        try{
            $q=$db->prepare("INSERT INTO products(category_id,supplier_id,name,sku,quantity,price,description) VALUES(:category_id,:supplier_id,:name,:sku,:quantity,:price,:description)");
            $q->execute([":category_id"=>(int)$old["category_id"],":supplier_id"=>(int)$old["supplier_id"],":name"=>$old["name"],":sku"=>$old["sku"],":quantity"=>(int)$old["quantity"],":price"=>(float)$old["price"],":description"=>$old["description"]]);
            flash("success","Produk berhasil ditambahkan."); redirect("index.php");
        }catch(PDOException $e){ $errors[]=$e->getCode()==="23000"?"SKU sudah digunakan.":"Produk gagal disimpan."; }
    }
}
$pageTitle="Tambah Produk"; require __DIR__."/includes/header.php";
?>
<section class="form-page"><div class="form-card">
<div class="eyebrow">＋ CREATE</div><h1>Tambah <span>Produk</span></h1><p class="muted">Masukkan data inventaris baru.</p>
<?php if($errors): ?><div class="alert error"><?php foreach($errors as $x): ?><div>• <?=e($x)?></div><?php endforeach;?></div><?php endif;?>
<form method="post" class="product-form"><input type="hidden" name="csrf_token" value="<?=e(csrfToken())?>">
<div class="form-grid">
<div class="field full"><label>Nama Produk</label><input name="name" value="<?=e($old["name"])?>" required></div>
<div class="field"><label>SKU</label><input name="sku" value="<?=e($old["sku"])?>" placeholder="INV-XXX-001" required></div>
<div class="field"><label>Kategori</label><select name="category_id" required><option value="">— Pilih Kategori —</option><?php foreach($categories as $c): ?><option value="<?=$c["id"]?>" <?=$old["category_id"]==$c["id"]?"selected":""?>><?=e($c["name"])?></option><?php endforeach;?></select></div>
<div class="field"><label>Supplier</label><select name="supplier_id" required><option value="">— Pilih Supplier —</option><?php foreach($suppliers as $s): ?><option value="<?=$s["id"]?>" <?=$old["supplier_id"]==$s["id"]?"selected":""?>><?=e($s["name"])?></option><?php endforeach;?></select></div>
<div class="field"><label>Stok</label><input type="number" min="0" name="quantity" value="<?=e($old["quantity"])?>" required></div>
<div class="field"><label>Harga (Rp)</label><input type="number" min="0" step="0.01" name="price" value="<?=e($old["price"])?>" required></div>
<div class="field full"><label>Deskripsi</label><textarea name="description" rows="4"><?=e($old["description"])?></textarea></div>
</div>
<div class="form-actions"><a class="btn ghost" href="index.php">Batal</a><button class="btn primary">Simpan Produk →</button></div>
</form></div></section>
<?php require __DIR__."/includes/footer.php"; ?>
