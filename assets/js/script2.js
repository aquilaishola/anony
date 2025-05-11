function copyToClipboard() {
    const link = document.getElementById('link');
    link.select();
    link.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(link.value).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'The link has been copied to your clipboard.',
            timer: 2000,
            showConfirmButton: false
        });
    });
}

function shareToFacebook() {
    const link = document.getElementById('link').value;
    const message = `Hey! Send me an anonymous message here - ${link}`;
    const fbShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(message)}`;
    window.open(fbShareUrl, '_blank');
}

function shareToWhatsApp() {
    const link = document.getElementById('link').value;
    const message = `Hey! Send me an anonymous message here - ${link}`;
    const waShareUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(waShareUrl, '_blank');
}

function shareToInstagram() {
    Swal.fire({
        icon: 'info',
        title: 'Instagram',
        text: 'Instagram does not support direct link sharing. Copy your link and paste it into your Instagram profile or story.',
    });
}