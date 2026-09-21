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
        'default_avatar' => 'public/cultivation/assets/images/neutral-profile.svg',
        'institution_name' => 'Our Institution',
        'subtle_platform_credit' => 'Developed & Powered By Cultivation',
    ],

    'hero' => [
        'fallback_slides' => [
            [
                'title' => 'A Place to Learn & Grow',
                'subtitle' => 'Creating a supportive environment for learning, values and student development.',
                'image' => 'public/img/mainbuilding.jpg',
                'button_text' => 'Discover More →',
                'button_url' => 'about-us',
            ],
            [
                'title' => 'Inspiring Learning Every Day',
                'subtitle' => 'Supporting students through structured learning, guidance and opportunity.',
                'image' => 'public/cultivation/assets/images/gallery/2.jpg',
                'button_text' => 'Academic Information →',
                'button_url' => 'syllabus',
            ],
            [
                'title' => 'Learning Beyond the Classroom',
                'subtitle' => 'Encouraging creativity, participation, discipline and responsible citizenship.',
                'image' => 'public/cultivation/assets/images/gallery/11.jpg',
                'button_text' => 'Explore Student Life →',
                'button_url' => 'image/gallary',
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
            'image' => 'public/cultivation/assets/images/neutral-profile.svg',
            'message' => 'We are committed to building a disciplined, inclusive institution where every learner can grow with confidence and purpose.',
        ],
        'head' => [
            'name' => 'Ms. Farhana Rahman',
            'designation' => 'Principal / Head of Institution',
            'image' => 'public/cultivation/assets/images/neutral-profile.svg',
            'message' => 'Our learning community brings together strong teaching, careful guidance and meaningful opportunities for every student.',
        ],
    ],

    'faculty' => [
        ['name' => 'Nusrat Jahan', 'designation' => 'Assistant Teacher', 'subject' => 'Bangla', 'photo' => 'public/cultivation/assets/images/team/style1/2.jpg'],
        ['name' => 'Imran Hossain', 'designation' => 'Assistant Teacher', 'subject' => 'Mathematics', 'photo' => 'public/cultivation/assets/images/team/style1/3.jpg'],
        ['name' => 'Maliha Sultana', 'designation' => 'Assistant Teacher', 'subject' => 'English', 'photo' => 'public/cultivation/assets/images/team/style1/1.jpg'],
        ['name' => 'Tanvir Ahmed', 'designation' => 'Assistant Teacher', 'subject' => 'Science', 'photo' => 'public/cultivation/assets/images/team/style1/4.jpg'],
        ['name' => 'Shamima Akter', 'designation' => 'Assistant Teacher', 'subject' => 'Social Studies', 'photo' => 'public/cultivation/assets/images/team/style1/5.jpg'],
        ['name' => 'Rezaul Karim', 'designation' => 'Assistant Teacher', 'subject' => 'Information Technology', 'photo' => 'public/cultivation/assets/images/team/style1/6.jpg'],
    ],

    'contact' => [
        'address' => 'Education Campus, Bangladesh',
        'phone' => '+880 1XXX-XXXXXX',
        'email' => 'office@school.example',
        'description' => 'A learning community dedicated to knowledge, good character and opportunity for every student.',
    ],

    'welcome' => [
        'heading' => 'Welcome to Our Institution',
        'sub_heading' => 'Knowledge, Discipline and Future Leadership',
        'details' => 'Our institution is dedicated to providing quality secondary education in a disciplined and supportive learning environment. Our mission is to foster academic excellence, ethical integrity, and leadership qualities in every student.',
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

    // Generic stock imagery; never presented as this institution's events.
    'gallery' => [
        ['title' => 'Classroom Learning', 'image' => 'public/cultivation/assets/images/gallery/2.jpg', 'description' => 'An illustration of classroom learning.'],
        ['title' => 'Student Development', 'image' => 'public/cultivation/assets/images/gallery/11.jpg', 'description' => 'An illustration of student development.'],
        ['title' => 'Academic Activities', 'image' => 'public/cultivation/assets/images/gallery/5.jpg', 'description' => 'An illustration of collaborative learning.'],
        ['title' => 'Campus Life', 'image' => 'public/cultivation/assets/images/gallery/7.jpg', 'description' => 'An illustration of campus life.'],
        ['title' => 'Learning Environment', 'image' => 'public/cultivation/assets/images/gallery/8.jpg', 'description' => 'An illustration of a learning environment.'],
    ],

    'empty_states' => [
        'notice' => 'No notices are currently published.',
        'gallery' => 'No gallery items available at this time.',
    ],
];
