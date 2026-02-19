function confirmDelete(productId)
{
    const confirmation = confirm(
        "Are you sure you want to delete this product?\n\nThis action cannot be undone."
    );

    if (!confirmation) return;

    document.getElementById("deleteProductId").value = productId;

    document.getElementById("deleteProductForm").submit();
}
