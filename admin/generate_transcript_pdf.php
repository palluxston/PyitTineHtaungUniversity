<?php
ob_start(); // Start output buffering
session_start();
require_once '../connect.php';
require_once('../tcpdf/tcpdf.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    exit('Unauthorized');
}

// Remove the K_PATH_IMAGES definition since it's already defined in TCPDF
class MYPDF extends TCPDF {
    public function Header() {
        // Logo
        $image_file = dirname(__FILE__) . '/../images/logo2.png';
        if(file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 180, 25);
        }
        $this->Ln(30);
    }
    
    public function Footer() {
        $this->SetY(-30);
        $this->SetFont('helvetica', 'I', 8);
        
        $this->Cell(0, 10, 'This transcript is not valid without the university seal and authorized signatures.', 0, false, 'C');
        $this->Ln(5);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'C');
    }
}


if (isset($_GET['student_id']) && isset($_GET['course_code'])) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                s.ID,
                s.full_name,
                c.Code as course_code,
                c.Title as course_title,
                a.AID,
                a.Title as assignment_title,
                a.full_marks,
                g.graded_mark
            FROM personal_details s
            JOIN enrollment e ON s.ID = e.SID
            JOIN courses c ON e.Code = c.Code
            JOIN assignment a ON c.Code = a.Code
            LEFT JOIN grade g ON a.AID = g.AID AND s.ID = g.SID
            WHERE s.ID = ? AND c.Code = ?
            ORDER BY a.deadline
        ");
        
        $stmt->execute([$_GET['student_id'], $_GET['course_code']]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($results) > 0) {
            // Create new PDF document
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Set document information
            $pdf->SetCreator('PTH University');
            $pdf->SetAuthor('PTH University');
            $pdf->SetTitle('Academic Transcript');
            
            // Set margins
            $pdf->SetMargins(15, 40, 15);
            $pdf->SetAutoPageBreak(TRUE, 35);
            
            // Add a page
            $pdf->AddPage();
            
            // Set font
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Academic Transcript', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Student Information
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(40, 7, 'Student ID:', 0);
            $pdf->Cell(60, 7, $results[0]['ID'], 0);
            $pdf->Cell(40, 7, 'Course Code:', 0);
            $pdf->Cell(50, 7, $results[0]['course_code'], 0, 1);
            
            $pdf->Cell(40, 7, 'Full Name:', 0);
            $pdf->Cell(60, 7, $results[0]['full_name'], 0);
            $pdf->Cell(40, 7, 'Course Title:', 0);
            $pdf->Cell(50, 7, $results[0]['course_title'], 0, 1);
            
            $pdf->Ln(10);
            
            // Create grades table
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetFillColor(0, 64, 128);
            $pdf->SetTextColor(255);
            $pdf->Cell(60, 10, 'Assignment', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Full Marks', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Grade', 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'Percentage', 1, 1, 'C', true);
            
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0);
            
            $totalMarks = 0;
            $totalPossible = 0;
            
            foreach($results as $row) {
                $percentage = $row['graded_mark'] ? number_format(($row['graded_mark'] / $row['full_marks']) * 100, 2) . '%' : '-';
                $pdf->Cell(60, 10, $row['assignment_title'], 1);
                $pdf->Cell(45, 10, $row['full_marks'], 1, 0, 'C');
                $pdf->Cell(45, 10, $row['graded_mark'] ?? '-', 1, 0, 'C');
                $pdf->Cell(40, 10, $percentage, 1, 1, 'C');
                
                // Calculate individual percentage for each assignment
                if ($row['graded_mark']) {
                    $percentages[] = ($row['graded_mark'] / $row['full_marks']) * 100;
                }
            }
            
            $overallPercentage = count($percentages) > 0 ? 
                number_format(array_sum($percentages) / count($percentages), 2) : 0;
            
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(150, 10, 'Overall Performance:', 1, 0, 'R');
            $pdf->Cell(40, 10, $overallPercentage . '%', 1, 1, 'C');
            
            $pdf->Ln(20);
            
            // Signatures
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(95, 10, '_____________________', 0, 0, 'C');
            $pdf->Cell(95, 10, '_____________________', 0, 1, 'C');
            $pdf->Cell(95, 10, 'Academic Registrar', 0, 0, 'C');
            $pdf->Cell(95, 10, 'Dean of Faculty', 0, 1, 'C');
            
            // Output PDF
            $pdf->Output('transcript_' . $results[0]['ID'] . '.pdf', 'I');
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>