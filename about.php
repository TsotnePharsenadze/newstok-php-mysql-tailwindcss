<?php
include("db/db.php");

//Layout Configuration
$title = "Newstok - About Us";
$includeSlider = true;
//EndLayout Configuration

if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu ORDER BY ord ASC");
    $menuArray = [];
    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }
    $_SESSION["menu"] = $menuArray;
}
?>

<?php
include("./layout/header.php");
?>

<main class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">About Us</h2>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Our Mission</h3>
        <p class="text-gray-700 mb-6">
            At Newstok, we aim to keep you informed with the latest news and stories from around the globe.
            Whether it's breaking news, in-depth analysis, or visual highlights, we're dedicated to delivering
            quality content.
        </p>

        <h3 class="text-xl font-semibold mb-4">Who We Are</h3>
        <p class="text-gray-700 mb-6">
            Newstok was founded in 2024 by a group of journalists and tech enthusiasts who believe in the power of
            information.
            Our team works tirelessly to bring you trustworthy, timely, and engaging stories in a way that’s
            accessible to everyone.
        </p>

        <h3 class="text-xl font-semibold mb-4">What We Offer</h3>
        <ul class="list-disc list-inside text-gray-700 mb-6">
            <li>Real-time news updates on diverse topics</li>
            <li>Curated photo galleries from global events</li>
            <li>Insightful articles and editorials</li>
            <li>Interactive multimedia content</li>
        </ul>

        <h3 class="text-xl font-semibold mb-4">Get in Touch</h3>
        <p class="text-gray-700">
            Have questions or suggestions? Feel free to reach out to us anytime.
            We value your feedback and look forward to connecting with you!
        </p>
    </div>
</main>

<?php
include("layout/footer.php");
?>