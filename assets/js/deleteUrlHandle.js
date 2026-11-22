function hapusFoto(e,deleteUrl,urlData){
    e.preventDefault();
    if(confirm('apakah anda yakin ingin menghapus foto ini?')){
        if(deleteUrl){
            window.open(deleteUrl, '_blank');
        }
         setTimeout(function() {
            window.location.href = urlData;
        }, 300);
    }
    return false;
}