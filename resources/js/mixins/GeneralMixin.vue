<script>
export default {
    name: "GeneralMixin",
    methods: {
        copyString(string) {
            const tempTextArea = document.createElement('textarea');

            tempTextArea.value = string;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
        }

    }
}
</script>
