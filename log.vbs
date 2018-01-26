Private Sub Workbook_Open()
On Error Resume Next
    Dim URL As String, Usr

    Usr = Default.GetUserName

    URL = "http://172.17.64.183/bi/sma_register.php?USER=32114&REPORT_NAME=24&ID=" & Usr & "&NAME=" & Usr

    Dim xml As Object
    Set xml = CreateObject("MSXML2.XMLHTTP")
    xml.Open "GET", URL, False
    xml.Send
End Sub
