<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= isset($html_title) ? $html_title: 'Bootstrap4'; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="includes/stylesheet/custom.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <!-- MULTIPLE IMAGE SELECT -->
  <script src="assets/js/spartan-multi-image-picker.js"></script>

  <style>
    @font-face {
      font-family: "pb_ssp_font";
      src: url("assets/fonts/SourceSansPro-Bold.ttf");
      src: url("assets/fonts/SourceSansPro-Bold.ttf") format("woff"),
      url("assets/fonts/SourceSansPro-Bold.ttf") format("opentype"),
      url("assets/fonts/SourceSansPro-Bold.ttf") format("svg");
    }
    
  	a:hover {
  		text-decoration: none;
  	}
    
    .cursor_pointer {
      cursor: pointer;
    }

    body {
      font-family: pb_ssp_font !important;
      font-weight: bold;
    }

    .bold-upper {
      text-transform: uppercase;
      font-weight: bold;
    }

    .btn-primary, .bg-primary {
      color: #fff !important;
      background-color: #FED403 !important;
      border-color: #FED403 !important;
    }

    .header_image {
      width: 500px;
    }
  </style>
</head>
<body>
