<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\ActivityCategory;
use App\Models\Activity;
use App\Models\Announcement;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 創建角色
        $roles = [
            ['name' => '訪客', 'slug' => 'guest', 'description' => '可瀏覽公開活動與公告'],
            ['name' => '一般使用者', 'slug' => 'user', 'description' => '可報名參加活動'],
            ['name' => '社員', 'slug' => 'member', 'description' => '享有提前報名權、福利查看及聊天室功能'],
            ['name' => '管理員', 'slug' => 'admin', 'description' => '可管理活動、公告與社員福利'],
            ['name' => '超級管理員', 'slug' => 'super', 'description' => '擁有全部權限，包括使用者管理'],
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
        
        // 創建超級管理員
        $admin = User::create([
            'name' => '系統管理員',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $admin->roles()->attach(Role::where('slug', 'super')->first()->id);
        
        // 創建活動分類
        $categories = [
            ['name' => '體驗潛水', 'slug' => 'experience', 'description' => '適合初學者的體驗活動'],
            ['name' => '進階潛水', 'slug' => 'advanced', 'description' => '適合有經驗的潛水員'],
            ['name' => '潛水認證', 'slug' => 'certification', 'description' => '各級潛水證照課程'],
            ['name' => '海洋生態', 'slug' => 'ecology', 'description' => '海洋生態觀察與保育活動'],
            ['name' => '社交活動', 'slug' => 'social', 'description' => '社員交流聚會活動'],
        ];
        
        foreach ($categories as $category) {
            ActivityCategory::create($category);
        }
        
        // 創建測試活動
        $activities = [
            [
                'title' => '墾丁體驗潛水一日遊',
                'description' => '適合初學者的體驗潛水活動，無需經驗即可參加。',
                'content' => '<p>本活動適合從未潛水的朋友參加，將由專業教練帶領下水，體驗水肺潛水的樂趣。</p><p>活動內容包括：</p><ul><li>基礎潛水理論</li><li>裝備使用介紹</li><li>淺水區練習</li><li>實際下水體驗（約30分鐘）</li></ul><p>請攜帶：泳裝、毛巾、個人藥品</p>',
                'start_date' => now()->addDays(30)->setTime(9, 0),
                'end_date' => now()->addDays(30)->setTime(17, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(25),
                'max_participants' => 10,
                'location' => '墾丁白砂灣',
                'price' => 2500,
                'activity_category_id' => 1,
                'is_published' => true,
            ],
            [
                'title' => '蘭嶼深潛三日遊',
                'description' => '適合有開放水域潛水證照的潛水員，將探索蘭嶼美麗的水下世界。',
                'content' => '<p>蘭嶼擁有台灣最美麗的珊瑚礁與海洋生物，此行程為期三天，共安排6次潛水活動。</p><p>行程特色：</p><ul><li>八代灣：豐富的珊瑚礁生態</li><li>玉女岩：壯觀的海底峽谷</li><li>雙獅岩：可與大型迴游魚群相遇</li></ul><p>請攜帶：個人潛水證照、潛水日誌、個人裝備（也可租借）</p>',
                'start_date' => now()->addDays(45)->setTime(8, 0),
                'end_date' => now()->addDays(47)->setTime(18, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(35),
                'max_participants' => 8,
                'location' => '台東蘭嶼',
                'price' => 12000,
                'activity_category_id' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'PADI開放水域潛水員課程',
                'description' => '國際認可的初級潛水證照課程，結業後可獲得PADI OW證照。',
                'content' => '<p>此課程包含理論課程、平靜水域訓練與開放水域實習四個部分，完成後可取得國際通用的PADI OW潛水證照。</p><p>課程內容：</p><ul><li>5堂理論課程</li><li>2天泳池訓練</li><li>4次開放水域實習潛水</li><li>教材與證照申請費用</li></ul><p>開課地點：台北市（理論與泳池）+ 東北角（開放水域）</p>',
                'start_date' => now()->addDays(14)->setTime(19, 0),
                'end_date' => now()->addDays(30)->setTime(17, 0),
                'registration_start' => now()->subDays(10),
                'registration_end' => now()->addDays(7),
                'max_participants' => 6,
                'location' => '台北市 + 東北角',
                'price' => 15000,
                'activity_category_id' => 3,
                'is_published' => true,
            ],
        ];
        
        foreach ($activities as $activity) {
            Activity::create($activity);
        }
        
        // 創建測試公告
        $announcements = [
            [
                'title' => '2025年度社員大會通知',
                'content' => '<p>親愛的社員們：</p><p>2025年度社員大會將於6月15日舉行，地點在台北市中正區市民大道100號3樓會議室。</p><p>議程包含：</p><ul><li>年度活動回顧</li><li>財務報告</li><li>下半年度活動計畫</li><li>幹部改選</li></ul><p>請社員們踴躍參加！</p>',
                'is_pinned' => true,
                'is_published' => true,
                'user_id' => $admin->id,
                'published_at' => now(),
            ],
            [
                'title' => '新增綠島潛點資訊',
                'content' => '<p>本社已更新綠島潛點資訊，包含石朗、柴口、大白沙等熱門潛點的詳細介紹與注意事項。</p><p>社員可登入查看完整資訊，作為下次潛水行程規劃參考。</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $admin->id,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => '2025年第二季潛水活動行事曆公布',
                'content' => '<p>2025年4月至6月的潛水活動行事曆已公布，包含：</p><ul><li>4月：墾丁春季潛水</li><li>5月：綠島深潛之旅</li><li>6月：蘭嶼珊瑚礁調查</li></ul><p>詳細活動內容請查看活動頁面，社員報名優先開放時間為活動前30天。</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $admin->id,
                'published_at' => now()->subDays(7),
            ],
        ];
        
        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}