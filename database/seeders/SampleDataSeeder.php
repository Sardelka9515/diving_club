<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // 確保有用戶存在來創建公告
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => '系統管理員',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        // 創建活動分類
        $categories = [
            ['name' => '體驗潛水', 'slug' => 'experience'],
            ['name' => '進階潛水', 'slug' => 'advanced'],
            ['name' => '潛水認證', 'slug' => 'certification'],
            ['name' => '海洋生態', 'slug' => 'ecology'],
            ['name' => '社交活動', 'slug' => 'social'],
        ];
        
        foreach ($categories as $category) {
            ActivityCategory::firstOrCreate($category);
        }
        
        // 創建範例活動
        $activities = [
            [
                'title' => '墾丁體驗潛水一日遊',
                'description' => '適合初學者的體驗潛水活動，無需經驗即可參加。專業教練全程指導，安全有保障。',
                'content' => '<h3>活動介紹</h3><p>本活動特別為初次接觸潛水的朋友設計，在專業教練的帶領下，您將安全地體驗水肺潛水的樂趣。</p><h3>活動內容</h3><ul><li>基礎潛水理論說明（30分鐘）</li><li>裝備使用介紹（30分鐘）</li><li>淺水區練習（30分鐘）</li><li>實際下水體驗（45分鐘）</li></ul><h3>注意事項</h3><ul><li>請攜帶：泳裝、毛巾、個人藥品</li><li>活動當天請勿飲酒</li><li>如有身體不適請主動告知教練</li></ul>',
                'start_date' => now()->addDays(10)->setTime(9, 0),
                'end_date' => now()->addDays(10)->setTime(17, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(7),
                'max_participants' => 10,
                'location' => '屏東縣恆春鎮墾丁白砂灣',
                'price' => 2500,
                'activity_category_id' => 1,
                'is_published' => true,
            ],
            [
                'title' => '蘭嶼深潛三日遊',
                'description' => '適合有開放水域潛水證照的潛水員。探索蘭嶼美麗的水下世界，包含住宿與餐食。',
                'content' => '<h3>行程特色</h3><p>蘭嶼擁有台灣最美麗的珊瑚礁與豐富的海洋生物，此行程為期三天兩夜，共安排6次潛水活動。</p><h3>潛點介紹</h3><ul><li><strong>八代灣</strong>：豐富的珊瑚礁生態，適合拍照</li><li><strong>玉女岩</strong>：壯觀的海底峽谷地形</li><li><strong>雙獅岩</strong>：有機會與大型迴游魚群相遇</li></ul><h3>包含項目</h3><ul><li>來回船票</li><li>2晚住宿（雙人房）</li><li>6次潛水（含裝備）</li><li>每日三餐</li><li>當地交通</li></ul>',
                'start_date' => now()->addDays(30)->setTime(8, 0),
                'end_date' => now()->addDays(32)->setTime(18, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(20),
                'max_participants' => 8,
                'location' => '台東縣蘭嶼鄉',
                'price' => 15000,
                'activity_category_id' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'PADI開放水域潛水員課程',
                'description' => '國際認可的初級潛水證照課程，結業後可獲得PADI OW證照，全球通用。',
                'content' => '<h3>課程介紹</h3><p>PADI開放水域潛水員課程是全球最受歡迎的潛水入門課程，完成後可取得國際通用的潛水證照。</p><h3>課程內容</h3><ul><li><strong>5堂理論課程</strong>：潛水物理、生理、裝備使用、安全技巧</li><li><strong>5堂平靜水域訓練</strong>：在泳池中練習基本技巧</li><li><strong>4次開放水域實習潛水</strong>：在海中完成認證要求</li></ul><h3>課程特色</h3><ul><li>小班制教學（最多6人）</li><li>經驗豐富的PADI教練</li><li>完善的安全設備</li><li>彈性的上課時間安排</li></ul><h3>費用包含</h3><ul><li>教材與學習資料</li><li>裝備使用</li><li>證照申請費用</li><li>泳池使用費</li></ul>',
                'start_date' => now()->addDays(14)->setTime(19, 0),
                'end_date' => now()->addDays(35)->setTime(17, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(7),
                'max_participants' => 6,
                'location' => '台北市信義區 + 東北角龍洞',
                'price' => 18000,
                'activity_category_id' => 3,
                'is_published' => true,
            ],
            [
                'title' => '綠島夜潛體驗',
                'description' => '體驗神秘的夜間海洋世界，觀察夜行性海洋生物的活動。',
                'content' => '<h3>夜潛介紹</h3><p>夜潛是截然不同的潛水體驗，在手電筒的照射下，您將看到白天看不到的夜行性生物。</p><h3>可能遇到的生物</h3><ul><li>夜行性魚類（如黃頭鸚哥魚）</li><li>各種蝦蟹類</li><li>海鰻與章魚</li><li>夜間開花的珊瑚</li></ul><h3>安全須知</h3><ul><li>必須持有開放水域潛水員證照</li><li>使用專業潛水手電筒</li><li>緊跟教練，不可擅自脫隊</li><li>建議有10次以上潛水經驗</li></ul>',
                'start_date' => now()->addDays(21)->setTime(18, 30),
                'end_date' => now()->addDays(21)->setTime(21, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(14),
                'max_participants' => 4,
                'location' => '台東縣綠島鄉中寮港',
                'price' => 3500,
                'activity_category_id' => 4,
                'is_published' => true,
            ],
            [
                'title' => '潛水社年度BBQ聚會',
                'description' => '社員年度聚會活動，享受美食與分享潛水經驗，增進彼此友誼。',
                'content' => '<h3>活動內容</h3><p>一年一度的潛水社大聚會，所有社員齊聚一堂，分享這一年來的潛水經歷與收穫。</p><h3>活動安排</h3><ul><li><strong>15:00-16:00</strong> 報到與交流</li><li><strong>16:00-17:30</strong> BBQ時間</li><li><strong>17:30-18:30</strong> 年度潛水照片分享</li><li><strong>18:30-19:30</strong> 遊戲與抽獎</li><li><strong>19:30-20:00</strong> 頒發年度獎項</li></ul><h3>免費提供</h3><ul><li>BBQ食材與設備</li><li>飲料與啤酒</li><li>現場音響設備</li><li>精美紀念品</li></ul>',
                'start_date' => now()->addDays(45)->setTime(15, 0),
                'end_date' => now()->addDays(45)->setTime(20, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(35),
                'max_participants' => 50,
                'location' => '新北市金山區朱銘美術館',
                'price' => 0,
                'activity_category_id' => 5,
                'is_published' => true,
            ],
        ];
        
        foreach ($activities as $activity) {
            Activity::create($activity);
        }
        
        // 創建範例公告
        $announcements = [
            [
                'title' => '2025年度社員大會通知',
                'content' => '<p><strong>親愛的社員們：</strong></p><p>2025年度社員大會將於<strong>6月15日（星期日）下午2:00</strong>舉行，地點在台北市中正區市民大道100號3樓會議室。</p><h3>會議議程</h3><ul><li>14:00-14:30 報到</li><li>14:30-15:00 年度活動回顧</li><li>15:00-15:30 財務報告</li><li>15:30-16:00 茶點時間</li><li>16:00-16:30 下半年度活動計畫</li><li>16:30-17:00 幹部改選</li><li>17:00-17:15 其他事項討論</li></ul><p><strong>請社員們踴躍參加！</strong></p><p>如有任何問題，請聯繫秘書部。</p>',
                'is_pinned' => true,
                'is_published' => true,
                'user_id' => $user->id,
                'published_at' => now(),
            ],
            [
                'title' => '新增綠島潛點資訊',
                'content' => '<p>好消息！本社已完成綠島潛點資訊的更新工作。</p><h3>更新內容包括：</h3><ul><li><strong>石朗潛點</strong>：詳細的進入點說明與安全注意事項</li><li><strong>柴口潛點</strong>：最新的海底地形圖與生物分佈資料</li><li><strong>大白沙潛點</strong>：潮汐時間表與最佳潛水時段建議</li></ul><p>社員可登入會員專區查看完整資訊，作為規劃綠島潛水行程的參考。</p><p>如有任何疑問，歡迎聯繫我們的技術委員會。</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $user->id,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => '2025年第二季潛水活動行事曆公布',
                'content' => '<p>2025年4月至6月的潛水活動行事曆現已公布！</p><h3>精彩活動預告：</h3><h4>4月份</h4><ul><li>4月6-7日：墾丁春季潛水（住宿兩天一夜）</li><li>4月14日：體驗潛水日（限定新手）</li><li>4月28日：海洋清潔日（環保潛水活動）</li></ul><h4>5月份</h4><ul><li>5月4-6日：綠島深潛之旅（三天兩夜）</li><li>5月18日：進階潛水技巧課程</li><li>5月25日：水下攝影工作坊</li></ul><h4>6月份</h4><ul><li>6月1-3日：蘭嶼珊瑚礁調查</li><li>6月15日：年度社員大會</li><li>6月29日：夏季BBQ聚會</li></ul><p><strong>社員報名優先權：</strong><br>社員享有活動前30天優先報名的權利。詳細活動內容請查看活動頁面。</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $user->id,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => '裝備維護與保養講座',
                'content' => '<p>為了幫助社員們更好地維護潛水裝備，延長使用壽命，我們特別舉辦裝備維護講座。</p><h3>講座資訊</h3><ul><li><strong>時間：</strong>3月22日（星期六）下午2:00-5:00</li><li><strong>地點：</strong>潛水社辦公室</li><li><strong>講師：</strong>王大明教練（15年維修經驗）</li></ul><h3>課程內容</h3><ol><li>調節器的基本拆解與清潔</li><li>BCD（浮力調整器）的維護要點</li><li>潛水衣的清洗與修補</li><li>面鏡與蛙鞋的保養技巧</li><li>裝備收納與儲存建議</li></ol><p><strong>費用：</strong>社員免費，非社員NT$500<br><strong>名額：</strong>限定20人</p><p>請攜帶您的個人裝備前來參加。報名請聯繫器材組。</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $user->id,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => '海洋保護月活動回顧',
                'content' => '<p>感謝所有參與2月海洋保護月活動的社員們！</p><h3>活動成果</h3><ul><li><strong>海底清潔：</strong>共清理了約50公斤的海底垃圾</li><li><strong>珊瑚復育：</strong>成功種植30株新的珊瑚苗</li><li><strong>環保教育：</strong>舉辦2場環保講座，參與人數超過100人</li><li><strong>海洋生物調查：</strong>記錄了82種不同的海洋生物</li></ul><h3>精彩照片</h3><p>本次活動的精彩照片已經上傳到社團相簿，歡迎大家下載分享。如果你也有拍到不錯的照片，歡迎提供給我們。</p><h3>下次活動預告</h3><p>下一次的海洋保護活動預計在6月舉行，屆時我們將前往東北角進行海洋生態調查，敬請期待！</p>',
                'is_pinned' => false,
                'is_published' => true,
                'user_id' => $user->id,
                'published_at' => now()->subDays(15),
            ],
        ];
        
        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}