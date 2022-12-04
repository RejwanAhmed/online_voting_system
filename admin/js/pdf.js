window.onload = function()
{
    document.getElementById("download").addEventListener("click",()=>
    {
        document.getElementById("election_name_year").classList.remove("col-lg-6");
        document.getElementById("election_name_year").classList.add("col-lg-12");
        const election_result = document.getElementById("election_result");
        var opt = {
            margin: 1,
            filename: 'election_result.pdf',
            pagebreak: {mode: ['avoid-all']},
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 2},
            jsPDF: { unit: 'in',format: 'a4', orientation: 'landscape'}
        };
        html2pdf().from(election_result).set(opt).save();
    });

}
