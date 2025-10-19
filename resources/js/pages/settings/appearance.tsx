import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import { appearance } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { SettingsNavItems } from '@/types/resources.d';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: appearance().url,
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Appearance settings" />

            <ResourcesLayout title="Settings" description="Manage your profile and account settings" sidebarNavItems={SettingsNavItems}>
                <div className="space-y-6">
                    <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                    <AppearanceTabs />
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
