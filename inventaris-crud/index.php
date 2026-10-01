<?php
require_once __DIR__ . "/config/bootstrap.php";

$search = clean($_GET["search"] ?? "");
$page = max(1,(int)($_GET["page"] ?? 1));
$perPage = 5;
$where = "";
$params = [];

if ($search !== "") {
    $where = "WHERE p.name LIKE :search OR p.sku LIKE :search OR c.name LIKE :search OR s.name LIKE :search";
    $params[":search"] = "%".$search."%";
}

$count = $db->prepare("SELECT COUNT(*) FROM products p INNER JOIN categories c ON c.id=p.category_id INNER JOIN suppliers s ON s.id=p.supplier_id {$where}");
$count->execute($params);
$total = (int)$count->fetchColumn();
$totalPages = max(1,(int)ceil($total/$perPage));
$page = min($page,$totalPages);
$offset = ($page-1)*$perPage;

$stmt = $db->prepare("SELECT p.*, c.name AS category_name, s.name AS supplier_name
FROM products p
INNER JOIN categories c ON c.id=p.category_id
INNER JOIN suppliers s ON s.id=p.supplier_id
{$where} ORDER BY p.id DESC LIMIT :limit OFFSET :offset");
foreach($params as $k=>$v) $stmt->bindValue($k,$v,PDO::PARAM_STR);
$stmt->bindValue(":limit",$perPage,PDO::PARAM_INT);
$stmt->bindValue(":offset",$offset,PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

$stats = $db->query("SELECT COUNT(*) total_products, COALESCE(SUM(quantity),0) total_stock, COALESCE(SUM(quantity*price),0) total_value FROM products")->fetch();

$pageTitle="Daftar Inventaris";
require __DIR__ . "/includes/header.php";
?>
<section class="hero">
<div><div class="eyebrow">📦 TUGAS RUTIN 8</div><h1>CRUD <span>Inventaris</span></h1><p>Kelola produk dengan PHP Native, PDO, MySQL, JOIN, prepared statements, dan foreign key.</p></div>
<a class="btn primary" href="product_create.php">＋ Tambah Produk</a>
</section>

<section class="stats">
<div class="stat"><span>Total Produk</span><strong><?= e((string)$stats["total_products"]) ?></strong></div>
<div class="stat"><span>Total Stok</span><strong><?= e((string)$stats["total_stock"]) ?></strong></div>
<div class="stat"><span>Nilai Inventaris</span><strong><?= e(rupiah($stats["total_value"])) ?></strong></div>
</section>

<section class="panel">
<div class="panel-head"><div><h2>Data Produk</h2><p>JOIN kategori + supplier</p></div>
<a class="btn secondary" href="export.php<?= $search!=="" ? "?search=".urlencode($search):"" ?>">↓ Export CSV</a></div>

<form class="search-form" method="get">
<input type="search" name="search" value="<?= e($search) ?>" placeholder="Cari nama, SKU, kategori, atau supplier...">
<button class="btn primary" type="submit">Cari</button>
<?php if($search!==""): ?><a class="btn ghost" href="index.php">Reset</a><?php endif; ?>
</form>

<div class="table-wrap"><table>
<thead><tr><th>Produk</th><th>SKU</th><th>Kategori</th><th>Supplier</th><th>Stok</th><th>Harga</th><th>Aksi</th></tr></thead>
<tbody>
<?php if(!$products): ?><tr><td colspan="7" class="empty">Data tidak ditemukan.</td></tr>
<?php else: foreach($products as $p): ?>
<tr>
<td><strong><?= e($p["name"]) ?></strong><small><?= e($p["description"] ?? "") ?></small></td>
<td><code><?= e($p["sku"]) ?></code></td>
<td><span class="badge"><?= e($p["category_name"]) ?></span></td>
<td><?= e($p["supplier_name"]) ?></td>
<td><?= e((string)$p["quantity"]) ?></td>
<td><?= e(rupiah($p["price"])) ?></td>
<td><div class="actions">
<a class="icon-btn edit" href="product_edit.php?id=<?= (int)$p["id"] ?>">✎</a>
<form class="inline-form" method="post" action="product_delete.php" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
<input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
<button class="icon-btn delete" type="submit">×</button>
</form>
</div></td>
</tr>
<?php endforeach; endif; ?>
</tbody></table></div>

<?php if($totalPages>1): ?><div class="pagination"><?php for($i=1;$i<=$totalPages;$i++): ?>
<a class="<?= $i===$page?"active":"" ?>" href="?page=<?=$i?><?= $search!=="" ? "&search=".urlencode($search):"" ?>"><?=$i?></a>
<?php endfor; ?></div><?php endif; ?>
</section>

<section class="requirements">
<div>✓ <strong>Prepared Statements</strong><small>PDO prepare/execute.</small></div>
<div>✓ <strong>3NF + Foreign Key</strong><small>Produk terhubung kategori & supplier.</small></div>
<div>✓ <strong>Sanitasi Output</strong><small>htmlspecialchars() digunakan.</small></div>
</section>
<?php require __DIR__ . "/includes/footer.php"; ?>
