<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo" style="">
            <a href="<?php echo base_url(); ?>">
                <img src="uploads/logo.png"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse" style="">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>

    <div style="border-top:1px solid rgba(69, 74, 84, 0.7);"></div>	
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>



        <!-- TEACHER -->
        <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/teacher_list">
                <i class="entypo-users"></i>
                <span><?php echo get_phrase('teacher'); ?></span>
            </a>
        </li>



        <!-- SUBJECT -->
        <li class="<?php if ($page_name == 'subject') echo ' active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/subject">
                <i class="entypo-docs"></i>
                <span><?php echo get_phrase('subject'); ?></span>
            </a>
        </li>

        <!-- CLASS ROUTINE -->
        <li class="<?php if ($page_name == 'class_routine') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/class_routine">
                <i class="entypo-target"></i>
                <span><?php echo get_phrase('class_routine'); ?></span>
            </a>
        </li>
        
		<!-- STUDY MATERIAL -->
        <li class="<?php if ($page_name == 'study_material') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/study_material">
                <i class="entypo-book-open"></i>
                <span><?php echo get_phrase('study_material'); ?></span>
            </a>
        </li>
		
		
		 <li class="<?php if ($page_name == 'exam_first' || $page_name == 'exam_second' || $page_name=='exam_site') echo 'active'; ?> ">
            <?php if ($this->session->userdata('cur_exam_data')) { ?>
                <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/exam_site">                
                    <i class="entypo-lock"></i>
                    <span><?php echo get_phrase('online_CBT'); ?></span>
                </a>
            <?php } else { ?>
                <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/exam/first">                
                    <i class="entypo-pencil"></i>
                    <span><?php echo get_phrase('online_CBT'); ?></span>
                </a>
            <?php } ?>
        </li>
		
		
	 <li class="<?php if ($page_name == 'exam_review' || $page_name == 'exam_result_detail') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/exam_review">
                        <span><i class="entypo-book"></i> <?php echo get_phrase('my_CBT_results'); ?></span>
                    </a>
                </li>
				
		 <!-- Exam marks -->
        <li class="<?php if ($page_name == 'media') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/media/<?php echo $this->session->userdata('login_user_id');?>">
                <i class="entypo-camera"></i>
                <span><?php echo get_phrase('view_media'); ?></span>
            </a>
        </li>		
		
		 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'news') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/news">
                <i class="fa fa-book"></i>
                <span><?php echo get_phrase('view_news'); ?></span>
            </a>
        </li>
		

        <!-- Exam marks -->
        <li class="<?php if ($page_name == 'student_marksheet') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/student_marksheet/<?php echo $this->session->userdata('login_user_id');?>">
                <i class="entypo-graduation-cap"></i>
                <span><?php echo get_phrase('exam_marks'); ?></span>
            </a>
        </li>

        <!-- PAYMENT -->
        <li class="<?php if ($page_name == 'invoice') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/invoice">
                <i class="entypo-credit-card"></i>
                <span><?php echo get_phrase('payment'); ?></span>
            </a>
        </li>
		
		 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'todays_thought') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/todays_thought">
                <i class="fa fa-book"></i>
                <span><?php echo get_phrase('todays_thought'); ?></span>
            </a>
        </li>
		

        <!-- LIBRARY -->
        <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/book">
                <i class="entypo-book"></i>
                <span><?php echo get_phrase('library'); ?></span>
            </a>
        </li>

        <!-- TRANSPORT -->
        <li class="<?php if ($page_name == 'transport') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/transport">
                <i class="entypo-location"></i>
                <span><?php echo get_phrase('transport'); ?></span>
            </a>
        </li>
		
			
			 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'holiday') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/holiday">
                <i class="entypo-target"></i>
                <span><?php echo get_phrase('holiday'); ?></span>
            </a>
        </li>

        <!-- NOTICEBOARD -->
        <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/noticeboard">
                <i class="entypo-doc-text-inv"></i>
                <span><?php echo get_phrase('noticeboard'); ?></span>
            </a>
        </li>

 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'assignment') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/assignment">
                <i class="fa fa-book"></i>
                <span><?php echo get_phrase('assignments'); ?></span>
            </a>
        </li>
        <!-- MESSAGE -->
        <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/message">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('message'); ?></span>
            </a>
        </li>
		
			 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'help_link') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/help_link">
                <i class="entypo-book-open"></i>
                <span><?php echo get_phrase('helpful_link'); ?></span>
            </a>
        </li>
		
			 <!-- TODAYS THOUGHT -->
        <li class="<?php if ($page_name == 'help_desk') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/help_desk">
                <i class="fa fa-table"></i>
                <span><?php echo get_phrase('help_desks'); ?></span>
            </a>
        </li>


        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>

    </ul>

</div>