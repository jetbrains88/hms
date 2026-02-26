<?php

namespace App\Helpers;

class UserHelper
{
    /**
     * Generate a role badge HTML
     */
    public static function roleBadge(string $role): string
    {
        $roleMap = [
            'admin' => [
                'color' => 'purple',
                'text' => 'Admin',
                'icon' => 'user-shield'
            ],
            'doctor' => [
                'color' => 'blue',
                'text' => 'Doctor',
                'icon' => 'user-md'
            ],
            'nurse' => [
                'color' => 'green',
                'text' => 'Nurse',
                'icon' => 'user-nurse'
            ],
            'pharmacy' => [
                'color' => 'yellow',
                'text' => 'Pharmacy',
                'icon' => 'pills'
            ],
            'lab' => [
                'color' => 'pink',
                'text' => 'Lab Technician',
                'icon' => 'flask'
            ],
            'reception' => [
                'color' => 'indigo',
                'text' => 'Receptionist',
                'icon' => 'headset'
            ],
            'patient' => [
                'color' => 'gray',
                'text' => 'Patient',
                'icon' => 'user-injured'
            ]
        ];

        $roleData = $roleMap[strtolower($role)] ?? [
            'color' => 'gray',
            'text' => ucfirst($role),
            'icon' => 'user'
        ];

        $colorClasses = [
            'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
            'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
            'green' => 'bg-green-100 text-green-800 border-green-200',
            'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'pink' => 'bg-pink-100 text-pink-800 border-pink-200',
            'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'gray' => 'bg-gray-100 text-gray-800 border-gray-200'
        ];

        return sprintf(
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border %s">
                <i class="fas fa-%s mr-1"></i>
                %s
            </span>',
            $colorClasses[$roleData['color']],
            $roleData['icon'],
            $roleData['text']
        );
    }

    /**
     * Generate a status badge HTML
     */
    public static function statusBadge(bool $status): string
    {
        if ($status) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                <i class="fas fa-check-circle mr-1"></i>
                Active
            </span>';
        }

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
            <i class="fas fa-times-circle mr-1"></i>
            Inactive
        </span>';
    }

    /**
     * Generate avatar HTML
     */
    public static function avatar(string $name, ?string $image = null, string $size = 'md'): string
    {
        $sizeClasses = [
            'sm' => 'w-8 h-8 text-sm',
            'md' => 'w-10 h-10 text-base',
            'lg' => 'w-12 h-12 text-lg',
            'xl' => 'w-16 h-16 text-xl'
        ];

        if ($image) {
            return sprintf(
                '<img src="%s" alt="%s" class="rounded-full %s object-cover">',
                $image,
                htmlspecialchars($name),
                $sizeClasses[$size]
            );
        }

        $initials = self::getInitials($name);
        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
        $color = $colors[array_rand($colors)];

        return sprintf(
            '<div class="rounded-full %s %s flex items-center justify-center text-white font-bold">
                %s
            </div>',
            $color,
            $sizeClasses[$size],
            $initials
        );
    }

    /**
     * Get user initials for avatar
     */
    public static function getInitials(string $name): string
    {
        $names = explode(' ', $name);
        $initials = '';

        foreach ($names as $n) {
            if (!empty($n)) {
                $initials .= strtoupper($n[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    public static function getVisitTypeColorClass($visitType): string
    {
        $typesColors = [
            'routine' => 'orange',
            'emergency' => 'red',
            'followup' => 'green',

        ];
        return $typesColors[$visitType] ?? 'gray';
    }


}
