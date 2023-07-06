$(document).ready(function () {
    $("#form").submit(function(event) {
        event.preventDefault();

        var selectedProductId = $("#product option:selected").val();
        var days = $("#customRange1").val();
        var services = [];

        $(".form-check-input:checked").each(function() {
            services.push($(this).val());
        });

        $.ajax({
            url: "./backend/utils.php", // Путь к серверному скрипту для обработки запроса
            method: "POST",
            data: { productId: selectedProductId, days: days },
            success: function(response) {
                var tariff = parseFloat(response);

                var totalCost;
                if (days === "") {
                    totalCost = "Укажите количество дней";
                }
                else{
                    totalCost = tariff * days;

                    if (services.length > 0) {
                        services.forEach(function (service) {
                            totalCost += parseFloat(service) * days;
                        })
                    }
                }

                console.log("Общая стоимость:", totalCost);
                $("#totalCost").text(totalCost);
            },
        });
    });
});