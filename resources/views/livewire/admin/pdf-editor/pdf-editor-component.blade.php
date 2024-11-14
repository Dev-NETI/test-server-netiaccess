<div>
    <canvas id="pdf-canvas"></canvas>

    <!-- Include PDF.js from a CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    
    <!-- Include pdf-lib via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

    <script>
        async function modifyPdf() {
            const url = '/storage/pdecertificate/Annex 1 Body page.pdf';   // Path to the PDF
            const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer());

            // Load a PDFDocument from the existing PDF bytes
            const pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);

            // Embed the first font in the library
            const helveticaFont = await pdfDoc.embedFont(PDFLib.StandardFonts.Helvetica);

            // Get the first page of the document
            const pages = pdfDoc.getPages();
            const firstPage = pages[0];

            // Add text to the first page
            firstPage.drawText('Edited Text', {
                x: 50,
                y: 700,
                size: 30,
                font: helveticaFont,
                color: PDFLib.rgb(0, 0, 1),
            });

            // Embed an image (replace the URL with an actual image path)
            // const imageUrl = '/path-to-image-from-laravel-storage';  // Example image URL
            // const imageBytes = await fetch(imageUrl).then(res => res.arrayBuffer());
            // const jpgImage = await pdfDoc.embedJpg(imageBytes);

            // // Draw the image
            // firstPage.drawImage(jpgImage, {
            //     x: 100,
            //     y: 500,
            //     width: 150,
            //     height: 150,
            // });

            // Serialize the PDFDocument to bytes
            const pdfBytes = await pdfDoc.save();

            // Create a Blob and download the modified PDF
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'modified.pdf';
            link.click();
        }

        // Run the modifyPdf function
        document.addEventListener('DOMContentLoaded', function () {
            modifyPdf();
        });
    </script>
</div>
