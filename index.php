<?php
session_start();
include_once("./php/all_status_invoices.php");
if (!$_SESSION["user_id"]) {
  header("Location: ./login.php");
  exit();
}
if (isset($_GET["logout"])) {
  session_unset();
  session_destroy();
  header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <!-- Font awesome cdn -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <!-- Poppins google fonts cdn -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <title>Loader Panel</title>
</head>

<body>
  <div id="container">
    <span id="openSidebarBtn"><i class="fa-solid fa-bars-staggered"></i>
    </span>
    <div id="sideBar">
      <div id="closeSidebarBtn"><i class="fa-solid fa-xmark"></i></div>
      <h2>Loader Panel</h2>
      <ul id="navLinks">
        <li class="navLink" data-id="request">
          <i class="fa-solid fa-paper-plane"></i> Request invoice
        </li>
        <li class="navLink" data-id="unpaid">
          <i class="fa-solid fa-wallet"></i> Unpaid invoices
        </li>
        <li class="navLink" data-id="pending">
          <i class="fa-solid fa-hourglass-half"></i> Pending invoices
        </li>
        <li class="navLink" data-id="paid">
          <i class="fa-solid fa-circle-check"></i> Paid invoices
        </li>
        <li class="navLink" data-id="rejected">
          <i class="fa-solid fa-circle-xmark"></i> Rejected invoices
        </li>
        <li class="navLink" data-id="sendEmail">
          <i class="fa-solid fa-envelope"></i> Send email
        </li>
        <li><a href="./index.php?logout=1" style="color: black; text-decoration: none;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      </ul>
    </div>
    <div id="mainContent">
      <!-- Request section -->
      <section class="section" id="request" style="display: none;">
        <form class="defaultForm" action="./php/request_invoice.php" method="post">
          <a href="./index.php" id="invoiceReqClose"><i class="fa fa-solid fa-xmark"></i></a>
          <h2>Invoice Request</h2>
          <div class="formElement">
            <label for="customer_email">Customer Email</label>
            <input type="email" name="customer_email" required />
          </div>
          <div class="formElement">
            <label for="customer_name">Customer Name</label>
            <input type="text" name="customer_name" required />
          </div>
          <div class="formElement">
            <label for="desired_product">Select Product</label>
            <select name="desired_product" id="desiredProduct" required>
              <option value="" style="display: none">Select</option>
              <option value="custom">Custom Amount</option>
              <option value="product_1">
                Single Product E-commerce Landing Page (Core coding) -> Price
                - $150
              </option>
              <option value="product_2">
                Multi Product E-commerce website (Frontend with Dashboard) ->
                Price - $320
              </option>
              <option value="product_3">
                Complete E-commerce Website with Admin Panel -> Price - $1080
              </option>
              <option value="product_4">
                Complete E-commerce Website without support -> Price - $750
              </option>
            </select>
          </div>
          <div class="formElement">
            <label for="invoice_amount">Invoice Amount</label>
            <input
              type="number"
              step="any"
              name="invoice_amount"
              id="invoiceAmount"
              min="1"
              readonly />
          </div>
          <div class="formElement">
            <button type="submit">Submit</button>
          </div>
        </form>
      </section>
      <!-- Unpaid section -->
      <section class="section" id="unpaid">
        <div class="sectionHeader">Unpaid invoices</div>
        <div class="loaderTable">
          <table>
            <thead>
              <th>SL</th>
              <th>Invoice Number</th>
              <th>Purpose/Product details</th>
              <th>Customer Name</th>
              <th>Amount</th>
              <th>Tax</th>
              <th>Payable</th>
              <th>Pay now</th>
            </thead>
            <tbody>
              <?php
              if ($unpaid_invoices && count($unpaid_invoices) > 0) {
                foreach ($unpaid_invoices as $unpaid) {
                  echo "
                    <tr>
                <td>{$unpaid["id"]}</td>
                <td>{$unpaid["invoice_number"]}</td>
                <td>{$unpaid["invoice_purpose"]}</td>
                <td>Tahmid Alam</td>
                <td>$ {$unpaid["invoice_amount"]}</td>
                <td>$ {$unpaid["cost"]}</td>
                <td>$ {$unpaid["payable_amount"]}</td>
                <td>
                  <input type='hidden' id='paymentId' name='paymentId' value='{$unpaid["invoice_link"]}'>
                  <a href='./index.php?payId={$unpaid["id"]}' target='_blank' class='payNowBtn'>Pay now</a>
                </td>
              </tr>
                    
                    ";
                }
              } else {
                echo "<tr><td colspan='12'>0 Unpaid invoices</td></tr>";
              }
              ?>

            </tbody>
          </table>
        </div>
      </section>
      <!-- Pending section -->
      <section class="section" id="pending" style="display: none">
        <div class="sectionHeader">Pending invoices</div>
        <div class="loaderTable">
          <table>
            <thead>
              <th>SL</th>
              <th>Invoice Number</th>
              <th>Purpose/Product details</th>
              <th>Customer Name</th>
              <th>Payable Amount</th>
              <th>Status</th>
            </thead>
            <tbody>
              <?php
              if ($pending_invoices  && count($pending_invoices) > 0) {
                foreach ($pending_invoices  as $pending) {
                  echo "<tr>
                <td>{$pending["id"]}</td>
                <td>{$pending["invoice_number"]}</td>
                <td>{$pending["invoice_purpose"]}</td>
                <td>{$pending["customer_name"]}</td>
                <td>$ {$pending["payable_amount"]}</td>
                <td style='color: orangered; font-weight: bold'>Pending</td>
              </tr>";
                }
              } else {
                echo "<tr><td colspan='12'>0 Pending invoices</td></tr>";
              }
              ?>

            </tbody>
          </table>
        </div>
      </section>
      <!-- Paid section -->
      <section class="section" id="paid" style="display: none">
        <div class="sectionHeader">Paid invoices</div>
        <div class="loaderTable">
          <table>
            <thead>
              <th>SL</th>
              <th>Invoice Number</th>
              <th>Purpose/Product details</th>
              <th>Customer Name</th>
              <th>Amount</th>
              <th>Status</th>
            </thead>
            <tbody>
              <?php
              if ($paid_invoices  && count($paid_invoices) > 0) {
                foreach ($paid_invoices  as $paid) {
                  echo "<tr>
                <td>{$paid["id"]}</td>
                <td>{$paid["invoice_number"]}</td>
                <td>{$paid["invoice_purpose"]}</td>
                <td>{$paid["customer_name"]}</td>
                <td>$ {$paid["payable_amount"]}</td>
                <td style='color: green; font-weight: bold; text-transform: Capitalize;'>{$paid["status"]}</td>
              </tr>";
                }
              } else {
                echo "<tr><td colspan='12'>0 Paid invoices</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </section>
      <!-- Rejected section -->
      <section class="section" id="rejected" style="display: none">
        <div class="sectionHeader">Rejected invoices</div>
        <div class="loaderTable">
          <table>
            <thead>
              <th>SL</th>
              <th>Invoice Number</th>
              <th>Purpose/Product details</th>
              <th>Customer Name</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Remark</th>
            </thead>
            <tbody>
              <?php
              if ($rejected_invoices  && count($rejected_invoices) > 0) {
                foreach ($rejected_invoices  as $rejected) {
                  echo "<tr>
                <td>{$rejected["id"]}</td>
                <td>{$rejected["invoice_number"]}</td>
                <td>{$rejected["invoice_purpose"]}</td>
                <td>{$rejected["customer_name"]}</td>
                <td>$ {$rejected["payable_amount"]}</td>
                <td style='color: red; font-weight: bold; text-transform: Capitalize;'>{$rejected["status"]}</td>
                <td>{$rejected["remark"]}</td>
              </tr>";
                }
              } else {
                echo "<tr><td colspan='12'>0 Rejected invoices</td></tr>";
              }
              ?>

            </tbody>
          </table>
        </div>
      </section>

      <!-- Send email section -->
      <div class="section" id="sendEmail" style="display: none;">

        <form action="./php/send_email.php" method="post">
          <h2>Send Email</h2>
          <select name="seSelect" id="seSelect" required>
            <option value="" style="display: none;">Select a product</option>
            <option value="product_1">Single Product E-commerce Landing page (Core codeing)</option>
            <option value="product_2">Multi-Products E-commerce Site (Front-End with Dashboard)</option>
            <option value="product_3">Complete E-commerce Website with Admin Panel</option>
            <option value="product_4">Complete E-commerce Website without support
            </option>
          </select>
          <input type="email" name="seEmail" id="seEmail" placeholder="To:" required>
          <input type="text" name="seSubject" id="seSubject" placeholder="Subject: select a product subject will automatically set" readonly>
          <textarea name="seBody" id="seBody" style="min-height: 250px; resize: none;display:none"></textarea>
          <div id="sePlaceholder">
            <p style="font-size: 14px; color:gray">Body: select a product email body will automatically set.</p>
          </div>
          <button class="btn" type="submit">Send Email</button>
        </form>
      </div>
    </div>
  </div>

  <!-- script for sidebar open and close -->
  <script>
    // Open sidebar
    document
      .getElementById("openSidebarBtn")
      .addEventListener("click", () => {
        document.getElementById("sideBar").style.display = "flex";
      });
    // Close sidebar
    document
      .getElementById("closeSidebarBtn")
      .addEventListener("click", () => {
        document.getElementById("sideBar").style.display = "none";
      });
  </script>
  <!-- Content hide and show -->
  <script>
    document.querySelectorAll(".navLink").forEach((nav) => {
      nav.addEventListener("click", () => {
        document.querySelectorAll(".navLink").forEach((nav) => {
          document.getElementById(nav.dataset.id).style.display = "none";
        });
        document.getElementById(nav.dataset.id).style.display = "flex";
      });
    });
  </script>
  <!-- Select product and show price -->
  <script>
    const desiredProduct = document.getElementById("desiredProduct");
    const invoiceAmount = document.getElementById("invoiceAmount");
    desiredProduct.addEventListener("change", (e) => {
      const value = e.target.value;
      if (value === "custom") {
        invoiceAmount.removeAttribute("readonly");
        invoiceAmount.required = true;
      }
      if (value === "product_1") {
        invoiceAmount.value = "150";
      } else if (value === "product_2") {
        invoiceAmount.value = "320";
      } else if (value === "product_3") {
        invoiceAmount.value = "1018";
      } else if (value === "product_4") {
        invoiceAmount.value = "750";
      }
    });
  </script>
  <!-- handle payment -->
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const payId = urlParams.get("payId");
    if (payId) {
      window.location.href = document.getElementById("paymentId").value;
    }
  </script>
  <!-- Send email product onchange handle -->
  <script>
    const seSelect = document.getElementById("seSelect");
    const seSubject = document.getElementById("seSubject");
    const seBody = document.getElementById("seBody");
    const sePlaceholder = document.getElementById("sePlaceholder");

    seSelect.addEventListener("change", (e) => {
      let value = e.target.value
      if (value === "product_1") {
        seSubject.value = "Thank you for purchasing Single Product E-commerce Landing page (Core codeing)";
        seBody.innerHTML = `
        <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333;font-size: 18px;">Product Description</h2><p style="color: #555555;margin-top:-15px">This project is a single-product e-commerce landing page with a front-end and an admin dashboard, all built using HTML, Tailwind CSS, and JavaScript.The front-end offers a smooth shopping experience with responsive product listings and a shopping cart, while the integrated dashboard provides a streamlined interface for managing products and orders.
          </p> <br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Template Features</h3><span style="color: #555555;">Single Product E-commerce Landing Page</span><br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Layout Features</h3><span style="color: #555555;">Front-End</span>
          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3><span style="color: #555555;">N/A</span> <br/>
          <h4 style="color: #333333; margin: 0px; font-size: 15px;">Best regards,</h4><span style="margin: 0px; color: #555555;">Soft-Tech Technology LLC</span>
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a> <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
        </td>
      </tr>
    </table>
  </body>
</html>

`;
        sePlaceholder.innerHTML = `
       <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333; margin: 0 0 8px 0; font-size: 18px;">Product Description</h2>
          <p style="margin: 0 0 10px 0; line-height: 1.5; color: #555555;">
            This project is a single-product e-commerce landing page with a front-end and an admin dashboard, all built using HTML, Tailwind CSS, and JavaScript. 
            The front-end offers a smooth shopping experience with responsive product listings and a shopping cart, 
            while the integrated dashboard provides a streamlined interface for managing products and orders.
          </p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Template Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Single Product E-commerce Landing Page</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Layout Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Front-End</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">N/A</p>

          <h4 style="color: #333333; margin: 20px 0 3px 0; font-size: 15px;">Best regards,</h4>
          <p style="margin: 2px 0; color: #555555;">Soft-Tech Technology LLC</p>
          <p style="margin: 3px 0;">
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a>
          </p>
          <p style="margin: 3px 0;">
            <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

`;
      } else if (value === "product_2") {
        seSubject.value = "Thank you for purchasing Multi-Products E-commerce Site (Front-End with Dashboard)";

        seBody.innerHTML = `
        <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333;font-size: 18px;">Product Description</h2><p style="color: #555555;margin-top:-15px">This project is a multi-product e-commerce site with a front-end and an admin dashboard, all built using HTML, Tailwind CSS, and JavaScript. The front-end offers a smooth shopping experience with responsive product listings and a shopping cart, while the integrated dashboard provides a streamlined interface for managing products and orders. The use of Tailwind CSS ensures a modern, mobile-first design, making the entire platform both visually appealing and highly functional across all devices.
          </p> <br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Template Features</h3><span style="color: #555555;">Multi-Products E-commerce Site</span>
          <span style="color: #555555;">Front-End</span>
          <span style="color: #555555;">Admin Panel</span>
          <span style="color: #555555;">One-page E-commerce Landing page</span><br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Layout Features</h3><span style="color: #555555;">Responsive Design</span>
          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3><span style="color: #555555;">N/A</span> <br/>
          <h4 style="color: #333333; margin: 0px; font-size: 15px;">Best regards,</h4><span style="margin: 0px; color: #555555;">Soft-Tech Technology LLC</span>
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a> <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
        </td>
      </tr>
    </table>
  </body>
</html>

`;
        sePlaceholder.innerHTML = `
       <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333; margin: 0 0 8px 0; font-size: 18px;">Product Description</h2>
          <p style="margin: 0 0 10px 0; line-height: 1.5; color: #555555;">
            This project is a multi-product e-commerce site with a front-end and an admin dashboard, all built using HTML, Tailwind CSS, and JavaScript. The front-end offers a smooth shopping experience with responsive product listings and a shopping cart, while the integrated dashboard provides a streamlined interface for managing products and orders. The use of Tailwind CSS ensures a modern, mobile-first design, making the entire platform both visually appealing and highly functional across all devices.
          </p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Template Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Multi-Products E-commerce Site</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Front-End</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Admin Panel</p>
          <p style="margin: 0 0 10px 0; color: #555555;">One-page E-commerce Landing page</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Layout Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Responsive Design</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">N/A</p>

          <h4 style="color: #333333; margin: 20px 0 3px 0; font-size: 15px;">Best regards,</h4>
          <p style="margin: 2px 0; color: #555555;">Soft-Tech Technology LLC</p>
          <p style="margin: 3px 0;">
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a>
          </p>
          <p style="margin: 3px 0;">
            <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

`;


      } else if (value === "product_3") {
        seSubject.value = "Thank you for purchasing Complete E-commerce Website with Admin Panel";

        seBody.innerHTML = `
        <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333;font-size: 18px;">Product Description</h2><p style="color: #555555;margin-top:-15px">This comprehensive e-commerce website with an integrated admin panel is your complete solution for online retail. The platform provides a seamless shopping experience for customers and gives you full control through an intuitive backend. Manage products, process orders, track inventory, and analyze sales effortlessly. Launch your online store quickly and professionally.</p> <br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Template Features</h3><span style="color: #555555;">Attractive Design</span>
          <span style="color: #555555;">Front-End and Back-End</span>
          <span style="color: #555555;">Admin panel</span><br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Layout Features</h3><span style="color: #555555;">Responsive Design</span>
          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3><span style="color: #555555;">Custome Database configuration.</span>
          <span style="color: #555555;">Website Customization</span><br/>
          <h4 style="color: #333333; margin: 0px; font-size: 15px;">Best regards,</h4><span style="margin: 0px; color: #555555;">Soft-Tech Technology LLC</span>
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a> <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
        </td>
      </tr>
    </table>
  </body>
</html>

`;
        sePlaceholder.innerHTML = `
       <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333; margin: 0 0 8px 0; font-size: 18px;">Product Description</h2>
          <p style="margin: 0 0 10px 0; line-height: 1.5; color: #555555;">
            This comprehensive e-commerce website with an integrated admin panel is your complete solution for online retail. The platform provides a seamless shopping experience for customers and gives you full control through an intuitive backend. Manage products, process orders, track inventory, and analyze sales effortlessly. Launch your online store quickly and professionally.
          </p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Template Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Attractive Design</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Front-End and Back-End</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Admin panel</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Layout Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Responsive Design</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Custome Database configuration.</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Website Customization</p>

          <h4 style="color: #333333; margin: 20px 0 3px 0; font-size: 15px;">Best regards,</h4>
          <p style="margin: 2px 0; color: #555555;">Soft-Tech Technology LLC</p>
          <p style="margin: 3px 0;">
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a>
          </p>
          <p style="margin: 3px 0;">
            <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

`;


      } else if (value === "product_4") {
        seSubject.value = "Thank you for purchasing Complete E-commerce Website without support";

        seBody.innerHTML = `
        <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333;font-size: 18px;">Product Description</h2><span style="color: #555555;margin-top:-15px">This comprehensive e-commerce website with an integrated admin panel is your complete solution for online retail. The platform provides a seamless shopping experience for customers and gives you full control through an intuitive backend. Manage products, process orders, track inventory, and analyze sales effortlessly. Launch your online store quickly and professionally.</span> <br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Template Features</h3><span style="color: #555555;">E-Commerce</span>
          <span style="color: #555555;">Front-End</span>
          <span style="color: #555555;">Back-End</span>
          <span style="color: #555555;">Admin Panel</span><br/>
          <h3 style="color: #333333; margin: 0px; font-size: 16px;">Layout Features</h3><span style="color: #555555;">Responsive Design</span>
          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3><span style="color: #555555;">N/A</span> <br/>
          <h4 style="color: #333333; margin: 0px; font-size: 15px;">Best regards,</h4><span style="margin: 0px; color: #555555;">Soft-Tech Technology LLC</span>
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a> <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
        </td>
      </tr>
    </table>
  </body>
</html>

`;
        sePlaceholder.innerHTML = `
       <html>
  <head>
    <meta charset="UTF-8">
    <title>Product Purchase Confirmation</title>
  </head>
  <body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; box-shadow: 0 0 8px rgba(0,0,0,0.08); border-collapse: collapse;">
      <tr>
        <td style="padding: 20px; text-align: left;">
          <h2 style="color: #333333; margin: 0 0 8px 0; font-size: 18px;">Product Description</h2>
          <p style="margin: 0 0 10px 0; line-height: 1.5; color: #555555;">
            This comprehensive e-commerce website with an integrated admin panel is your complete solution for online retail. The platform provides a seamless shopping experience for customers and gives you full control through an intuitive backend. Manage products, process orders, track inventory, and analyze sales effortlessly. Launch your online store quickly and professionally.
          </p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Template Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">E-Commerce</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Front-End</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Back-End</p>
          <p style="margin: 0 0 10px 0; color: #555555;">Admin Panel</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Layout Features</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">Responsive Design</p>

          <h3 style="color: #333333; margin: 15px 0 5px 0; font-size: 16px;">Support</h3>
          <p style="margin: 0 0 10px 0; color: #555555;">N/A</p>

          <h4 style="color: #333333; margin: 20px 0 3px 0; font-size: 15px;">Best regards,</h4>
          <p style="margin: 2px 0; color: #555555;">Soft-Tech Technology LLC</p>
          <p style="margin: 3px 0;">
            <a href="https://soft-techtechnologyllc.com/" style="color: #007BFF; text-decoration: none;">Visit Our Website</a>
          </p>
          <p style="margin: 3px 0;">
            <a href="mailto:contact@soft-techtechnologyllc.com" style="color: #007BFF; text-decoration: none;">
              contact@soft-techtechnologyllc.com
            </a>
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

`;

      }
    })
  </script>

</body>

</html>