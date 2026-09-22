<!DOCTYPE html>
<html id="clearance">
<style>
    @media print {
        .noprint {
        visibility: hidden;
         }
    }
    @page { size: auto;  margin: 3mm; }
</style>

 <head>
    <meta charset="UTF-8">
    <title><?= $resident['lname'];?>,     <?= $resident['fname'];?>    <?= $resident['mi'];?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../BarangaySystem-master/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="../BarangaySystem-master/bootstrap/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="../BarangaySystem-master/bootstrap/css/morris-0.4.3.min.css" rel="stylesheet" type="text/css" />
    <link href="../BarangaySystem-master/bootstrap/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="../BarangaySystem-master/bootstrap/css/select2.css" rel="stylesheet" type="text/css" />
    <script src="../BarangaySystem-master/bootstrap/css/jquery-1.12.3.js" type="text/javascript"></script>
</head>
 <body class="skin-black">
    <?php

     // Fetch the staff member with position "Punong Barangay"
     $stmt_pb = $conn->prepare("SELECT fname, mi, lname FROM tbl_user WHERE position = 'Punong Barangay' LIMIT 1");
     $stmt_pb->execute();
     $punong = $stmt_pb->fetch(PDO::FETCH_ASSOC);
     $punong_name = $punong ? strtoupper($punong['lname'] . ', ' . $punong['fname'] . ' ' . $punong['mi']) : 'PUNONG BARANGAY';

     // Format the issue date into "DAY of MONTH, YEAR" pieces
     $issue_ts   = !empty($resident['date']) ? strtotime($resident['date']) : time();
     $issue_day  = date('jS', $issue_ts);
     $issue_month= date('F', $issue_ts);
     $issue_year = date('Y', $issue_ts);
     ?>
    <style>
        @media print {
            .noprint { display: none !important; }
            @page { size: landscape; margin: 0; }
            body { margin: 0; padding: 0; }
            .main-wrapper { border: none !important; width: 100% !important; height: 100vh; }
        }

        .main-wrapper { display: flex; background: white; font-family: 'Arial', sans-serif; }
        .sidebar { width: 280px; border-right: 3px double #000; padding: 30px 15px; text-align: center; }
        .content-area { flex-grow: 1; padding: 40px 60px; }
        .official-p { margin-bottom: 30px; line-height: 1.1; }
        .official-name { margin-bottom: 10px; font-weight: bold; display: block; font-size: 14px; text-transform: uppercase; }
        .official-title { margin-bottom: 30px; font-size: 10px; display: block; font-style: italic; color: #444; }
    </style>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Livestock Ownership - <?php echo $resident['lname']; ?></title>
    <style>
       body {
    background-color: #f0f0f0;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
}

.certificate {
    width: 210mm; /* A4 Width */
    min-height: 297mm; /* A4 Height */
    padding: 20mm;
    margin: 20px auto;
    background: white;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    position: relative;
    box-sizing: border-box;
}

/* Header Styling */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: center;
}

.seal1 {
    width: 100px;
    height: 100px;
}
.seal2 {
    width: 100px;
    height: 100px;
}

.header-text p {
    margin: 2px 0;
    font-size: 14px;
}

.office-title {
    text-align: center;
    font-family: 'Times New Roman', serif;
    font-size: 22px;
    font-weight: bold;
    color: #003366;
    margin-top: 20px;
    border-bottom: 2px solid #003366;
    padding-bottom: 5px;
}

h1 {
    text-align: center;
    font-size: 28px;
    margin-top: 30px;
    text-transform: capitalize;
}

/* Body Text */
.content {
    margin-top: 50px;
    line-height: 1.8;
    text-align: justify;
    font-size: 16px;
}

.salutation {
    margin-bottom: 30px;
    font-weight: bold;
}

/* Footer & Signature */
.footer {
    margin-top: 80px;
    display: flex;
    justify-content: flex-end;
}

.signature-block {
    text-align: center;
    width: 300px;
}

.signature-block .name {
    text-decoration: underline;
    margin-bottom: 0;
}

.watermark-note {
    position: absolute;
    bottom: 40px;
    left: 40px;
    font-size: 12px;
    font-style: italic;
    color: #888;
}

/* Print Settings */
@media print {
    body { background: none; }
    .certificate {
        margin: 0;
        border: none;
        box-shadow: none;
    }
    @page {
        size: A4;
        margin: 0;
    }
}
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <img src="icons/logo.png" class="seal1 left" alt="Barangay Seal">
            <div class="header-text">
                <p>REPUBLIC OF THE PHILIPPINES</p>
                <p>PROVINCE OF CAMARINES SUR</p>
                <p>CITY OF IRIGA</p>
                <p><strong>BARANGAY SAN PEDRO</strong></p>
            </div>
            <img src="icons/Documents/seal.png" class="seal2 right" alt="City Seal">
        </div>

        <div class="office-title">
            OFFICE OF THE PUNONG BARANGAY
        </div>

        <h1>CERTIFICATE OF LIVESTOCK OWNERSHIP</h1>

        <div class="content">
            <p class="salutation">TO WHOM IT MAY CONCERN:</p>

            <p>THIS IS TO CERTIFY that <strong><?= $resident['lname'];?>, <?= $resident['fname'];?> <?= $resident['mi'];?></strong>, of legal age, <strong><?= $resident['civil_status'];?></strong>, and a resident of <strong><?= $resident['houseno'];?> <?= $resident['street'];?></strong>, Barangay <strong><?= $resident['brgy'];?></strong>, <strong><?= $resident['municipal'];?></strong>, is the rightful and lawful owner of the following livestock/animal:</p>

            <table style="margin: 0 auto 20px; font-size: 15px;">
                <tr><td style="padding:3px 12px 3px 0;">Animal Type:</td><td><strong><?= $resident['animal_type'];?></strong></td></tr>
                <tr><td style="padding:3px 12px 3px 0;">Sex:</td><td><strong><?= $resident['animal_sex'];?></strong></td></tr>
                <tr><td style="padding:3px 12px 3px 0;">Age/Color:</td><td><strong><?= $resident['age_color'];?></strong></td></tr>
                <tr><td style="padding:3px 12px 3px 0;">Brand/Marks:</td><td><strong><?= $resident['brand_marks'] ?: 'None';?></strong></td></tr>
                <tr><td style="padding:3px 12px 3px 0;">Quantity:</td><td><strong><?= $resident['quantity'];?></strong></td></tr>
            </table>

            <p>FURTHER CERTIFIED that the above-mentioned animal is healthy, raised within this barangay, and free from any known contagious or infectious diseases.</p>

            <p>THIS CERTIFICATION is issued upon the request of the owner for <strong><?= $resident['purpose'];?></strong> purposes.</p>

            <p>ISSUED this <strong><?= $issue_day;?></strong> day of <strong><?= $issue_month;?></strong>, <strong><?= $issue_year;?></strong> at Barangay San Pedro, Iriga City, Philippines.</p>
        </div>

        <div class="footer">
            <div class="signature-block">
                <br><br>
                <p class="name"><strong><?= htmlspecialchars($punong_name); ?></strong></p>
                <p>PUNONG BARANGAY</p>
            </div>
        </div>

        <div class="watermark-note">
            Not valid w/o official seal
        </div>
    </div>
</html>

    <div class="noprint" style="display: flex; justify-content: center; align-items: center; gap: 15px; margin: 30px 0;">
    <button
        type="button"
        class="noprint"
        id="printpagebutton"
        style="padding: 12px 40px; font-size: 18px; font-weight: bold; border-radius: 5px; background-color: #198754; color: #fff; border: none; cursor: pointer;"
        onclick="PrintElem('#clearance')">
        Print Certificate
    </button>
    <a href="admn_certoflivestock.php" class="noprint" style="padding: 12px 40px; font-size: 18px; font-weight: bold; border-radius: 5px; background-color: #6c757d; color: #fff; border: none; text-decoration: none; display: inline-block; text-align: center;">
        Back to Admin
    </a>
    </div>
</body>

    <script>
         function PrintElem(elem)
    {
        window.print();
    }
    </script>
</html>
