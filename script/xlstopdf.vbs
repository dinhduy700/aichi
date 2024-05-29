Dim Excel
Dim ExcelDoc

Set arguments = Wscript.Arguments.Named

'Opens the Excel file'
Set Excel = CreateObject("Excel.Application")
Set ExcelDoc = Excel.Workbooks.open(arguments.Item("ExcelFile"))

'Creates the pdf file'
Excel.ActiveSheet.ExportAsFixedFormat 0, arguments.Item("PdfFile") ,0, 1, 0,,,0

'Closes the Excel file'
Excel.ActiveWorkbook.Close
Excel.Application.Quit
