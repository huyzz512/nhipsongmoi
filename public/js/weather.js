document.addEventListener("DOMContentLoaded", function () {
    // Tọa độ địa lý của Hà Nội
    const lat = 21.0285;
    const lon = 105.8542;
    // Gọi API của Open-Meteo (Miễn phí, không cần API Key)
    const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`;

    fetch(weatherUrl)
        .then(response => response.json())
        .then(data => {
            const weather = data.current_weather;
            const temp = Math.round(weather.temperature); // Làm tròn nhiệt độ
            const code = weather.weathercode; // Mã quy định trạng thái thời tiết

            let desc = "Trời quang";
            let iconCode = "fa-sun";
            let iconColor = "#f39c12";

            // Phân loại mã thời tiết sang Tiếng Việt và đổi Icon FontAwesome tương ứng
            if (code === 1 || code === 2 || code === 3) {
                desc = "Có mây"; iconCode = "fa-cloud-sun"; iconColor = "#95a5a6";
            } else if (code >= 45 && code <= 48) {
                desc = "Sương mù"; iconCode = "fa-smog"; iconColor = "#7f8c8d";
            } else if (code >= 51 && code <= 67) {
                desc = "Mưa nhỏ"; iconCode = "fa-cloud-rain"; iconColor = "#3498db";
            } else if (code >= 80 && code <= 82) {
                desc = "Mưa rào"; iconCode = "fa-cloud-showers-heavy"; iconColor = "#2980b9";
            } else if (code >= 95) {
                desc = "Mưa dông"; iconCode = "fa-bolt"; iconColor = "#c0392b";
            }

            // Đổ dữ liệu thật vào HTML
            const weatherHtml = `<i class="fas ${iconCode}" style="color: ${iconColor};"></i> ${temp}°C - ${desc}`;
            document.getElementById('live-weather').innerHTML = weatherHtml;
        })
        .catch(error => {
            // Hiển thị mặc định nếu API bị lỗi (mất mạng)
            document.getElementById('live-weather').innerHTML = `<i class="fas fa-cloud"></i> Không tải được thời tiết`;
        });
});