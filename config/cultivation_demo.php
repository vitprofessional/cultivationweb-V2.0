<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cultivation V2 Centralized Demo & Fallback Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized fallback source for demo/template content when real database
    | records or assets are absent. Real database data always takes priority.
    |
    */

    'branding' => [
        'default_logo' => 'public/logo.png',
        'default_avatar' => 'public/avatar.jpeg',
        'subtle_platform_credit' => 'Developed & Powered By Cultivation',
    ],

    'hero' => [
        'fallback_slides' => [
            [
                'title' => 'Knowledge, Discipline & Future Leadership',
                'subtitle' => 'Creating a supportive environment for learning, values and student development.',
                'image' => 'public/cultivation/assets/images/slider/h2-1.jpg',
                'button_text' => 'Discover More →',
                'button_url' => 'about-us',
            ],
            [
                'title' => 'Inspiring Learning Every Day',
                'subtitle' => 'Supporting students through structured learning, guidance and opportunity.',
                'image' => 'public/cultivation/assets/images/slider/h2-2.jpg',
                'button_text' => 'Notice Board →',
                'button_url' => 'notices',
            ],
            [
                'title' => 'Learning Beyond the Classroom',
                'subtitle' => 'Encouraging creativity, participation, discipline and responsible citizenship.',
                'image' => 'public/cultivation/assets/images/slider/main-home/1.jpg',
                'button_text' => 'Class Routine →',
                'button_url' => 'class/schedule',
            ],
        ],
    ],

    'statistics' => [
        'established' => ['value' => '2004', 'label' => 'Established', 'icon' => 'fa-university'],
        'students' => ['value' => '850+', 'label' => 'Students', 'icon' => 'fa-graduation-cap'],
        'teachers' => ['value' => '28+', 'label' => 'Teachers', 'icon' => 'fa-users'],
        'staff' => ['value' => '12+', 'label' => 'Staff Members', 'icon' => 'fa-user'],
        'classes' => ['value' => '10+', 'label' => 'Classes & Programs', 'icon' => 'fa-book'],
        'experience' => ['value' => '20+', 'label' => 'Years of Service', 'icon' => 'fa-calendar'],
    ],

    'leadership' => [
        'chairman' => [
            'name' => 'Mr. Abdul Karim',
            'designation' => 'Governing Body Chairman',
            'image' => 'public/avatar.jpeg',
            'message' => 'We are committed to building a disciplined, inclusive institution where every learner can grow with confidence and purpose.',
        ],
        'head' => [
            'name' => 'Ms. Farhana Rahman',
            'designation' => 'Principal / Head of Institution',
            'image' => 'public/avatar.png',
            'message' => 'Our learning community brings together strong teaching, careful guidance and meaningful opportunities for every student.',
        ],
    ],

    'faculty' => [
        ['name' => 'Nusrat Jahan', 'designation' => 'Assistant Teacher', 'subject' => 'Bangla', 'photo' => 'public/upload/image/teacher/158272875020250811.jpg'],
        ['name' => 'Imran Hossain', 'designation' => 'Assistant Teacher', 'subject' => 'Mathematics', 'photo' => 'public/upload/image/teacher/166889356020250208.jpg'],
        ['name' => 'Maliha Sultana', 'designation' => 'Assistant Teacher', 'subject' => 'English', 'photo' => 'public/upload/image/teacher/192712606820250209.jpeg'],
        ['name' => 'Tanvir Ahmed', 'designation' => 'Assistant Teacher', 'subject' => 'Science', 'photo' => 'public/upload/image/teacher/210863459020250205.jpg'],
        ['name' => 'Shamima Akter', 'designation' => 'Assistant Teacher', 'subject' => 'Social Studies', 'photo' => 'public/upload/image/teacher/53511580820250413.jpeg'],
        ['name' => 'Rezaul Karim', 'designation' => 'Assistant Teacher', 'subject' => 'Information Technology', 'photo' => 'public/upload/image/teacher/59363862320231024.jpg'],
    ],

    'contact' => [
        'address' => 'Sankuchail, Bangladesh',
        'phone' => '+880 1XXX-XXXXXX',
        'email' => 'info@institution.edu.bd',
    ],

    'welcome' => [
        'heading' => 'Welcome to Sankuchail High School',
        'sub_heading' => 'Knowledge, Discipline and Future Leadership',
        'details' => 'Sankuchail High School is dedicated to providing quality secondary education in a disciplined and supportive learning environment. Our mission is to foster academic excellence, ethical integrity, and leadership qualities in every student.',
        'image' => 'public/img/mainbuilding.jpg',
    ],

    'news_events' => [
        [
            'category' => 'Academic Environment',
            'title' => 'Academic Guidance & Curriculum Orientation',
            'image' => 'public/cultivation/assets/images/about/tab1.jpg',
            'summary' => 'Fostering structured learning, session planning, and comprehensive academic growth for all students.',
        ],
        [
            'category' => 'Co-Curricular Life',
            'title' => 'Sports, Physical Wellbeing & Teamwork',
            'image' => 'public/cultivation/assets/images/about/tab2.jpg',
            'summary' => 'Encouraging physical health, disciplined teamwork, and healthy athletic participation.',
        ],
        [
            'category' => 'Student Development',
            'title' => 'Science Demonstration & Student Projects',
            'image' => 'public/cultivation/assets/images/about/tab3.jpg',
            'summary' => 'Cultivating student creativity, critical thinking, and practical scientific inquiry.',
        ],
    ],

    'admission' => [
        'title' => 'Admission Information',
        'subtitle' => 'Contact the institution directly for current admission guidelines, eligibility, and academic requirements.',
        'contact_prompt' => 'For admission inquiries, please visit the administrative office during office hours.',
    ],

    'empty_states' => [
        'notice' => 'No notices are currently published.',
        'gallery' => 'No gallery items available at this time.',
    ],
];
