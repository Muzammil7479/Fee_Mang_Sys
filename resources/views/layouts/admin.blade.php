<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('title')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#f4f6f9;
}

.sidebar{

    width:250px;

    height:100vh;

    background:#1b263b;

    position:fixed;

    color:white;

}

.sidebar h3{

    padding:20px;

    text-align:center;

}

.sidebar a{

    color:white;

    display:block;

    padding:15px;

    text-decoration:none;

}

.sidebar a:hover{

    background:#415a77;

}

.main{

    margin-left:250px;

}

.topbar{

    background:white;

    padding:15px;

    box-shadow:0 2px 5px rgba(0,0,0,.2);

}

.content{

    padding:30px;

}

.card-box{

    border-radius:12px;

    color:white;

    padding:20px;

    margin-bottom:20px;

}

.blue{

    background:#0d6efd;

}

.green{

    background:#198754;

}

.orange{

    background:#fd7e14;

}

.red{

    background:#dc3545;

}

</style>

</head>

<body>

<div class="sidebar">

<h3>EduNexus</h3>

<a href="/admin">

<i class="fa fa-home"></i>

Dashboard

</a>

<a href="/teachers">

<i class="fa fa-chalkboard-teacher"></i>

Teachers

</a>

<a href="/student">

<i class="fa fa-user-graduate"></i>

Students

</a>

<a href="/account-section">

<i class="fa fa-wallet"></i>

Accounts

</a>

<a href="/principal">

<i class="fa fa-user-tie"></i>

Principal

</a>

</div>

<div class="main">

<div class="topbar">

<h4>@yield('heading')</h4>

</div>

<div class="content">

@yield('content')

</div>

</div>

</body>

</html>