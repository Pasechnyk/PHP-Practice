$(document).ready(function() {
    // Task 1 - Image preview and form validation
    // Preview selected image
    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    // Trigger file input on click
    $("#preview-image").click(function() {
        $("#image").click();
    });
    // Trigger function when a picture is selected
    $("#image").change(function() {
        readURL(this);
    });

    // Form validation
    $("form").submit(function(event) {
        let name = $("#name").val();
        let image = $("#image").val();

        if (!name || !image) {
            alert("Please fill in the name and select a photo first!");
            event.preventDefault();
        }
    });

    // Task 2 - Delete function
    // Delete category
    $(document).on("click", ".delete-btn", function(e) {
        e.preventDefault();
        // Confirmation pop-up
        let deleteConfirmed = confirm("Are you sure you want to delete this category?");
        if (deleteConfirmed) {
            let categoryId = $(this).data('category-id');
            // Debug check
            console.log("Category ID to delete: ", categoryId);
            // AJAX request
            $.ajax({
                type: "POST",
                url: "/delete_category.php", // Separate PHP file for deletions
                data: { id: categoryId },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    console.error("Error deleting category:", error);
                }
            });
        }
    });
});