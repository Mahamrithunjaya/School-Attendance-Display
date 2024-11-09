<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start(); // Start session

if (!isset($_SESSION['class'])) {
    die('Class session variable is not set.'); // Handle the error appropriately
}

$class = $_SESSION['class'];

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include("../connect.php");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Query to fetch attendance data from the database
$sql = "SELECT students.roll_number, students.student_name, students.class, attendance_records.month,
 attendance_records.total_days, attendance_records.attendance, attendance_records.attendance_percentage
        FROM attendance_records
        JOIN students ON attendance_records.roll_number = students.roll_number
        WHERE students.class = '".$_SESSION['class']."'";

$result = $conn->query($sql);

if ($result) {
    // Setting the header for the Excel sheet
    $sheet->setCellValue('A1', 'Admission Number');
    $sheet->setCellValue('B1', 'Student Name');
    $sheet->setCellValue('C1', 'Month');
    $sheet->setCellValue('D1', 'Total Classes');
    $sheet->setCellValue('E1', 'Attended Classes');
    $sheet->setCellValue('F1', 'Attendance Percentage');

    // Setting the width for each column
    $sheet->getColumnDimension('A')->setWidth(25); // Set width for Admission Number column
    $sheet->getColumnDimension('B')->setWidth(30); // Set width for Student Name column
    $sheet->getColumnDimension('C')->setWidth(20); // Set width for Month column
    $sheet->getColumnDimension('D')->setAutoSize(true); // Set width for Total Classes column
    $sheet->getColumnDimension('E')->setAutoSize(true); // Set width for Attended Classes column
    $sheet->getColumnDimension('F')->setAutoSize(true); // Set width for Attendance Percentage column

    // Making the header row bold, increase font size
    $headerStyleArray = [
        'font' => [
            'bold' => true,
            'size' => 16, // Setting the font size to 12 (adjust as needed)
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center horizontally
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Center vertically
        ],
    ];
    $sheet->getStyle('A1:F1')->applyFromArray($headerStyleArray);



    $rowNumber = 2; // Start from the second row (first row for header)

    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['roll_number']);
        $sheet->setCellValue('B' . $rowNumber, $row['student_name']);
        $sheet->setCellValue('C' . $rowNumber, $row['month']);
        $sheet->setCellValue('D' . $rowNumber, $row['total_days']);
        $sheet->setCellValue('E' . $rowNumber, $row['attendance']);
        $sheet->setCellValue('F' . $rowNumber, $row['attendance_percentage'] / 100);


        // Applying conditional formatting for attendance percentage < 75%
        if ($row['attendance_percentage'] < 75) {
            $sheet->getStyle('A' . $rowNumber . ':F' . $rowNumber)
                  ->getFont()
                  ->getColor()
                  ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED); // Set font color to red
        }

        $rowNumber++;
    }

    // Making the content rows center horizontally and vertically
    $contentStyleArray = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center horizontally
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Center vertically
        ],
    ];
    $sheet->getStyle('B:F')->applyFromArray($contentStyleArray);

    // Applying percentage format to the 'Attendance Percentage' column
    $sheet->getStyle('F2:F' . ($rowNumber - 1))
          ->getNumberFormat()
          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);


    // Applying borders to all cells within the used range
    $usedRange = 'A1:F' . ($rowNumber - 1); // From A1 to the last used row
    $borderStyleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'], // Set border color (black)
            ],
        ],
    ];
    $sheet->getStyle($usedRange)->applyFromArray($borderStyleArray);


    $writer = new Xlsx($spreadsheet);
    $fileName = "attendance_$class.xlsx";

    // Prompt download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
} else {
    die('Query failed: ' . $conn->error);
}

// Close connection
$conn->close();

?>
