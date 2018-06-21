function copy(id) {
    /* Select the text field */
    const elementById = document.getElementById(id);
    console.log(elementById);
    elementById.select();
    // document.getElementById(id).select();

    /* Copy the text inside the text field */
    document.execCommand("copy");

    console.log('copied');
}
