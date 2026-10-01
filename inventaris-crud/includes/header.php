<?php
$pageTitle = $pageTitle ?? "Inventaris";
$flash = getFlash();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?> — Tugas Rutin 8</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
<div class="header-inner">
<a class="brand" href="index.php"><span class="brand-mark">08</span> INVENTARIS</a>
<nav><a href="index.php">Dashboard</a><a href="product_create.php">+ Produk</a></nav>
</div>
</header>
<main class="page">
<?php if ($flash): ?><div class="flash <?= e($flash["type"]) ?>"><?= e($flash["message"]) ?></div><?php endif; ?>
