 document.addEventListener("DOMContentLoaded", function () {
            const messageBox = document.getElementById('message');
            const counter = document.getElementById('counter');
            const sendButton = document.getElementById('send-btn');

            messageBox.addEventListener('input', function () {
                const length = messageBox.value.length;
                counter.textContent = `${length} / 300`;
                if (length >= 300) {
                    messageBox.value = messageBox.value.substring(0, 300);
                    counter.textContent = "300 / 300";
                }
            });
        });