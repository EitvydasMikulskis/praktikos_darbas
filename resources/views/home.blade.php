<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panelė</title>

<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: Arial, sans-serif;
    }

    body{
        background:#f4f6f9;
    }

    .sidebar{
        width:300px;
        height:100vh;
        background:#ffffff;
        box-shadow:0 4px 15px rgba(0,0,0,0.1);
        position:fixed;
        left:0;
        top:0;
    }

    .sidebar-header{
        background:#2c3e50;
        color:white;
        text-align:center;
        padding:20px;
        font-size:22px;
        font-weight:bold;
        letter-spacing:1px;
    }

    .menu{
        list-style:none;
        padding:15px;
    }

    .menu li{
        margin-bottom:12px;
    }

    .menu a{
        display:block;
        text-decoration:none;
        background:#f8f9fb;
        color:#333;
        padding:14px 16px;
        border-radius:8px;
        transition:0.3s;
        font-size:16px;
        font-weight:500;
    }

    .menu a:hover{
        background:#3498db;
        color:white;
        transform:translateX(5px);
    }

    .content-container{
        background:#ffffff;
        margin-left:320px;
        margin-top:30px;
        margin-right:30px;
        padding:30px;

        border-radius:20px;

        box-shadow:0 4px 15px rgba(0,0,0,0.08);

        min-height:calc(100vh - 60px);
    }
</style>
</head>
<body>

<div class="sidebar">  
    <div class="sidebar-header">
        PVM SĄSKAITOS-FAKTŪROS
    </div>

    <ul class="menu">
        <li><a href="/create-client">Sukurti klientą</a></li>
        <li><a href="/client-list">Klientų sąrašas</a></li>
        <li><a href="/create-product">Sukurti prekę</a></li>
        <li><a href="/product-list">Prekių sąrašas</a></li>
        <li><a href="/new-invoice">Nauja sąskaita</a></li>
        <li><a href="/invoice-list">Sąskaitų sąrašas</a></li>
        <li><a href="/pdf-export">PDF eksportas</a></li>
        <li><a href="/invoice-send">Siųsti el. paštu</a></li>
    </ul>
</div>

<div class="content-container">

</div>

</body>
</html>