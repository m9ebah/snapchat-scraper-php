
# Snapchat Scraper PHP Library
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![License](https://img.shields.io/badge/License-GPL--3.0-blue)
![Repo Size](https://img.shields.io/github/repo-size/m9ebah/snapchat-scraper-php)
![Open Issues](https://img.shields.io/github/issues/m9ebah/snapchat-scraper-php)
![GitHub stars](https://img.shields.io/github/stars/m9ebah/snapchat-scraper-php?style=social)

📸 **Project Description | وصف المشروع:**  

`Snapchat Scraper PHP Library` is a PHP library designed to scrape public Snapchat stories. The library allows you to retrieve media content (images and videos) along with timestamps from publicly available Snapchat stories, without affecting privacy or accessing private content.  
مكتبة `Snapchat Scraper PHP Library` هي مكتبة PHP مصممة لاستخراج القصص العامة من سناب شات. تتيح لك المكتبة جلب محتوى الوسائط (صور وفيديوهات) مع التواريخ الزمنية من القصص العامة المتاحة على سناب شات فقط، دون التأثير على الخصوصية أو الوصول إلى المحتوى الخاص.

---

## 🚀 **Installation Steps | خطوات التثبيت:**

### 1. **Install the Library using Composer | تثبيت المكتبة باستخدام Composer:**
```bash
composer require m9ebah/snapchat-scraper-php
```

---

## ⚙️ **How to Use the Library | كيفية استخدام المكتبة:**

### 1. **Initialize the Client and Scraper | تهيئة العميل والمكتبة:**

```php
<?php

require 'vendor/autoload.php';

use SnapchatScraper\Clients\HttpClient;
use SnapchatScraper\StoryDownloader;
use SnapchatScraper\SnapchatScraper;

// Initialize the client
$httpClient = new HttpClient();

// Specify where to save the downloaded files
$downloadDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'downloads';

// Prepare the story download library
$storyDownloader = new StoryDownloader($downloadDirectory);

// Configure the main library
$scraper = new SnapchatScraper($httpClient, $storyDownloader);

// Specify the user name
$username = 'example_user';  // Example of a username

// Fetch Snapchat data
$snapchatData = $scraper->getSnapchatData($username);

// Fetch stories
$stories = $snapchatData['stories'];

// If there are stories, upload them.
if (!empty($stories)) {
    echo "Loading stories...\n";
    $storyDownloader->download($stories);
    echo "Stories uploaded successfully.\n";
} else {
    echo "There are no stories for this user.\n";
}
```




## 📸 **Example Output | مثال على المخرجات:**

The library will download media files (images and videos) for the given username and save them to the specified `downloads` folder.  
المكتبة ستقوم بتنزيل الملفات الوسائط (صور وفيديوهات) لاسم المستخدم المحدد وحفظها في مجلد `downloads`.

---

## 🤝 **Contribution | المساهمة:**

We welcome all contributions to improve the library. You can open an Issue or submit a Pull Request.  
نرحب بجميع المساهمات لتحسين المكتبة. يمكنك فتح مشكلة (Issue) أو إرسال طلب سحب (Pull Request).
