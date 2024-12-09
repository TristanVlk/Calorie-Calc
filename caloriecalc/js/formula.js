document.getElementById('calorie-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const age = parseInt(document.getElementById('age').value);
    const gender = document.getElementById('gender').value;
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const activity = parseFloat(document.getElementById('activity').value);

    let bmr;
    if (gender === 'male') {
        bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5;
    } else {
        bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161;
    }

    const totalCalories = bmr * activity;
    const maintainWeight = totalCalories;
    const mildWeightLoss = totalCalories * 0.90;
    const weightLoss = totalCalories * 0.80;
    const extremeWeightLoss = totalCalories * 0.61;

    const resultHTML = `
        <div class="result-box">
            <div class="result-item">
                <div class="label">Maintain weight</div>
                <div class="value">${maintainWeight.toFixed(0)} Calories/day</div>
                <div class="percentage">100%</div>
            </div>
            <div class="result-item">
                <div class="label">Mild weight loss</div>
                <div class="value">${mildWeightLoss.toFixed(0)} Calories/day</div>
                <div class="percentage">80%</div>
                <div class="info">0.5 lb/week</div>
            </div>
            <div class="result-item">
                <div class="label">Weight loss</div>
                <div class="value">${weightLoss.toFixed(0)} Calories/day</div>
                <div class="percentage">60%</div>
                <div class="info">1 lb/week</div>
            </div>
            <div class="result-item">
                <div class="label">Extreme weight loss</div>
                <div class="value">${extremeWeightLoss.toFixed(0)} Calories/day</div>
                <div class="percentage">50%</div>
                <div class="info">2 lb/week</div>
            </div>
        </div>
    `;

    document.getElementById('result').innerHTML = resultHTML;

    // Save calculation to history
    fetch('save_calculation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            maintainWeight: maintainWeight.toFixed(0),
            mildWeightLoss: mildWeightLoss.toFixed(0),
            weightLoss: weightLoss.toFixed(0),
            extremeWeightLoss: extremeWeightLoss.toFixed(0)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Calculation saved successfully.");
        } else {
            console.error("Error saving calculation:", data.error);
        }
    })
    .catch(error => console.error("Fetch error:", error));
})