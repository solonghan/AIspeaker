<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trace_event_model extends Base_Model
{
	// 1.login
	const EVENT_LOGIN = "login";

	// 2.App Launched （完成帳號註冊才紀錄）
	const EVENT_APP_LAUNCHED = "app_launched";

	// 3.帳號註冊完成事件
	const EVENT_REGISTRATION_COMPLETED = 'registration_completed';

	// 4.教學導引啟動和結束事件
	const EVENT_TUTORIAL_STARTED = 'tutorial_started';

	// 5.教學導引結束事件
	const EVENT_TUTORIAL_COMPLETED = 'tutorial_completed';

	// 6.語句辨識頁面。(首頁按鍵)
	const EVENT_HOME_BUTTON_CLICKED = 'home_button_clicked';

	// 7. 點擊「溝通練習室」事件。
	const EVENT_COMMUNICATION_ROOM_CLICKED = 'communication_room_clicked';

	// 8.啟動「一般文本的語句錄音」事件。
	const EVENT_REGULAR_AUDIO_RECORDING_STARTED = 'regular_audio_recording_started';

	// 9. 啟動「自定義的語句錄音」事件。
	const EVENT_CUSTOM_AUDIO_RECORDING_STARTED = 'custom_audio_recording_started';

	// 10. 完成新增專屬語句字詞事件。
	const EVENT_EXCLUSIVE_PHRASE_ADDED = 'exclusive_phrase_added';

	// 10. 達成每日錄音最高點數回饋事件。
	const EVENT_DAILY_RECORDING_GOAL_REACHED = 'daily_recording_goal_reached';

	// 11. 點擊「訊息聊天室」事件
	const EVENT_MESSAGE_CHATROOM_CLICKED = 'message_chatroom_clicked';

	// 12. 聊天室好友新增
	const EVENT_FRIEND_ADDED_IN_CHATROOM = 'friend_added_in_chatroom';

	// 12. 聊天室好友刪除
	const EVENT_FRIEND_REMOVED_IN_CHATROOM = 'friend_removed_in_chatroom';

	// 13. 聊天室群組新增事件
	const EVENT_GROUP_ADDED_IN_CHATROOM = 'group_added_in_chatroom';

	// 13. 聊天室群組刪除事件
	const EVENT_GROUP_REMOVED_IN_CHATROOM = 'group_removed_in_chatroom';

	// 14. 點擊「會員中心」事件
	const EVENT_MEMBER_CENTER_CLICKED = 'member_center_clicked';
	
	// 15. 付款訂閱事件
	const EVENT_SUBSCRIPTION_PURCHASED = 'subscription_purchased';

	// 16. VAD參數值調整事件
	const EVENT_VAD_PARAMETER_ADJUSTED = 'vad_parameter_adjusted';

	// 17. 客服成功送出事件
	const EVENT_CUSTOMER_SERVICE_REQUEST_SENT = 'customer_service_request_sent';


	const EVENTS_ARRAY = [
		self::EVENT_LOGIN,
		self::EVENT_APP_LAUNCHED,
		self::EVENT_REGISTRATION_COMPLETED,
		self::EVENT_TUTORIAL_STARTED,
		self::EVENT_TUTORIAL_COMPLETED,
		self::EVENT_HOME_BUTTON_CLICKED,
		self::EVENT_COMMUNICATION_ROOM_CLICKED,
		self::EVENT_REGULAR_AUDIO_RECORDING_STARTED,
		self::EVENT_CUSTOM_AUDIO_RECORDING_STARTED,
		self::EVENT_EXCLUSIVE_PHRASE_ADDED,
		self::EVENT_DAILY_RECORDING_GOAL_REACHED,
		self::EVENT_MESSAGE_CHATROOM_CLICKED,
		self::EVENT_FRIEND_ADDED_IN_CHATROOM,
		self::EVENT_FRIEND_REMOVED_IN_CHATROOM,
		self::EVENT_GROUP_ADDED_IN_CHATROOM,
		self::EVENT_GROUP_REMOVED_IN_CHATROOM,
		self::EVENT_MEMBER_CENTER_CLICKED,
		self::EVENT_SUBSCRIPTION_PURCHASED,
		self::EVENT_VAD_PARAMETER_ADJUSTED,
		self::EVENT_CUSTOMER_SERVICE_REQUEST_SENT
	  ];
	  
	
	// 登入/註冊 頁面
	const APP_PAGE_LOGIN_OR_REGISTER = 'app_page_login_or_register';

	// 登入 頁面
	const APP_PAGE_LOGIN = 'app_page_login';

	// 建立帳號
	const APP_PAGE_CREATE_ACCOUNT = 'app_page_create_account';

	// 驗證電子郵件
	const APP_PAGE_VERIFY_EMAIL = 'app_page_verify_email';

	// 填寫個人資料_個案
	const APP_PAGE_FILL_IN_PERSONAL_INFO = 'app_page_fill_in_personal_info';

	// 忘記密碼
	const APP_PAGE_FORGOT_PASSWORD = 'app_page_forgot_password';

	// 主畫面
	const APP_PAGE_HOME = 'app_page_home';

	// 語音辨識頁面
	const APP_PAGE_VOICE_RECOGNITION = 'app_page_voice_recognition';

	// 訊息聊天室
		// 聊天室列表
		const APP_PAGE_CHATROOM_LIST = 'app_page_chatroom_list';

		// 新增好友
		const APP_PAGE_CHATROOM_ADD_FRIEND = 'app_page_chatroom_add_friend';

		// 建立群組
		const APP_PAGE_CHATROOM_CREATE_GROUP = 'app_page_chatroom_create_group';

		// 編輯群組名稱
		const APP_PAGE_CHATROOM_EDIT_GROUP_NAME = 'app_page_chatroom_edit_group_name';

		// 聊天室訊息
		const APP_PAGE_CHATROOM_MESSAGE = 'app_page_chatroom_message';

		// 群組資訊頁面
		const APP_PAGE_CHATROOM_GROUP_INFO = 'app_page_chatroom_group_info';

		// 群組成員編輯
		const APP_PAGE_CHATROOM_EDIT_GROUP_MEMBER = 'app_page_chatroom_edit_group_member';

	
	// 溝通練習室頁面
		// 語句分類清單
		const APP_PAGE_COMMUNICATION_ROOM_PHRASE_CATEGORIES = 'app_page_communication_room_phrase_categories';

		// 專屬語句新增
		const APP_PAGE_COMMUNICATION_ROOM_PHRASE_ADD = 'app_page_communication_room_phrase_add';

		// 專屬語句清單
		const APP_PAGE_COMMUNICATION_ROOM_PHRASE_LIST = 'app_page_communication_room_phrase_list';

		// 專屬語句錄製
		const APP_PAGE_COMMUNICATION_ROOM_RECORDING = 'app_page_communication_room_recording';


	// 會員中心
		// 主頁
		const APP_PAGE_MEMBER_CENTER = 'app_page_member_center';

		// 個人檔案編輯頁
		const APP_PAGE_MEMBER_CENTER_PROFILE = 'app_page_member_center_profile';

		// 訂閱方案
		const APP_PAGE_MEMBER_CENTER_SUBSCRIBE = 'app_page_member_center_subscribe';

		// 付款紀錄
		const APP_PAGE_MEMBER_CENTER_PAYMENT_RECORD = 'app_page_member_center_payment_record';

		// 字體大小設定
		const APP_PAGE_MEMBER_CENTER_FONT_SETTING = 'app_page_member_center_font_setting';


		// 常見問題
		const APP_PAGE_MEMBER_CENTER_FAQ = 'app_page_member_center_faq';

		// 聯絡客服
		const APP_PAGE_MEMBER_CENTER_CONTACT_US = 'app_page_member_center_contact_us';

		// 聯絡客服傳訊
		const APP_PAGE_MEMBER_CENTER_CONTACT_US_MESSAGE = 'app_page_member_center_contact_us_message';


		const APP_PAGES_ARRAY = [
			self::APP_PAGE_LOGIN_OR_REGISTER,
			self::APP_PAGE_CREATE_ACCOUNT,
			self::APP_PAGE_LOGIN,
			self::APP_PAGE_VERIFY_EMAIL,
			self::APP_PAGE_FILL_IN_PERSONAL_INFO,
			self::APP_PAGE_FORGOT_PASSWORD,
			self::APP_PAGE_HOME,
			self::APP_PAGE_VOICE_RECOGNITION,
			self::APP_PAGE_CHATROOM_LIST,
			self::APP_PAGE_CHATROOM_ADD_FRIEND,
			self::APP_PAGE_CHATROOM_CREATE_GROUP,
			self::APP_PAGE_CHATROOM_EDIT_GROUP_NAME,
			self::APP_PAGE_CHATROOM_MESSAGE,
			self::APP_PAGE_CHATROOM_GROUP_INFO,
			self::APP_PAGE_CHATROOM_EDIT_GROUP_MEMBER,
			self::APP_PAGE_COMMUNICATION_ROOM_PHRASE_CATEGORIES,
			self::APP_PAGE_COMMUNICATION_ROOM_PHRASE_ADD,
			self::APP_PAGE_COMMUNICATION_ROOM_PHRASE_LIST,
			self::APP_PAGE_COMMUNICATION_ROOM_RECORDING,
			self::APP_PAGE_MEMBER_CENTER,
			self::APP_PAGE_MEMBER_CENTER_PROFILE,
			self::APP_PAGE_MEMBER_CENTER_SUBSCRIBE,
			self::APP_PAGE_MEMBER_CENTER_PAYMENT_RECORD,
			self::APP_PAGE_MEMBER_CENTER_FONT_SETTING,
			self::APP_PAGE_MEMBER_CENTER_FAQ,
			self::APP_PAGE_MEMBER_CENTER_CONTACT_US,
			self::APP_PAGE_MEMBER_CENTER_CONTACT_US_MESSAGE,
		  ];
		  

		public function traceEvent($user, $eventName, $appVersion=null, $appLang=null, $deviceInfo=null, $deviceFirmware=null)
		{
			$traceParam = [
				"user" => $user,
				"event" => $eventName,
				"app_version" => $appVersion,
				"app_lang" => $appLang,
				"device_info" => $deviceInfo,
				"device_firmware" => $deviceFirmware
			];
			$this->db->insert('track_event', $traceParam);

			if (!in_array($eventName, self::EVENTS_ARRAY)) {
				return "event未定義";
			} else {
				return "success";
			}
		}

		public function tracePage($user, $appPageName, $appPageStayTime)
		{
			$traceParam = [
				"user"	=> $user,
				"app_page_name" => $appPageName,
				"app_page_stay_time" => $appPageStayTime
			];
			$this->db->insert('track_event', $traceParam);

			if (!in_array($appPageName, self::APP_PAGES_ARRAY)) {
				return "page未定義";
			} else {
				return "success";
			}
		}







}