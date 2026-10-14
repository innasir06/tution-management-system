<?php
require('fpdf/fpdf.php');
require('db.php');

$id = $_GET['id'];
$data = $conn->query("SELECT f.*, s.name, s.class FROM fees f JOIN students s ON f.student_id = s.student_id WHERE f.fee_id = $id")->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Header
$pdf->Cell(0,10,'TUITION MANAGEMENT SYSTEM - RECEIPT',0,1,'C');
$pdf->Ln(10);

// Student Info
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Student Name: ' . $data['name'], 0, 1);
$pdf->Cell(0,10,'Class: ' . $data['class'], 0, 1);
$pdf->Cell(0,10,'Date: ' . date('d-M-Y'), 0, 1);
$pdf->Ln(5);

// Fee Table
$pdf->SetFillColor(200,200,200);
$pdf->Cell(90,10,'Description',1,0,'C',true);
$pdf->Cell(90,10,'Amount',1,1,'C',true);

$pdf->Cell(90,10,'Total Monthly Fee',1,0);
$pdf->Cell(90,10,'$' . $data['total_fee'],1,1,'R');

$pdf->Cell(90,10,'Amount Paid',1,0);
$pdf->Cell(90,10,'$' . $data['amount_paid'],1,1,'R');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,10,'Remaining Balance',1,0);
$pdf->Cell(90,10,'$' . ($data['total_fee'] - $data['amount_paid']),1,1,'R');

$pdf->Ln(20);
$pdf->Cell(0,10,'Authorized Signature: __________________',0,1,'R');

$pdf->Output('D', 'Receipt_'.$data['name'].'.pdf');
?>