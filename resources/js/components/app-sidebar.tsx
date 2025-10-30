import AppearanceTabs from '@/components/appearance-tabs';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { TeamSwitcher } from '@/components/team-switcher';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import company from '@/routes/company';
import database from '@/routes/database';
import project from '@/routes/project';
import { SharedData } from '@/types';
import { type NavItem } from '@/types/resources';
import { Link, usePage } from '@inertiajs/react';
import { LayoutGrid } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Companies',
        href: company.index().url,
        icon: null,
    },
    {
        title: 'Projects',
        href: project.index().url,
        icon: null,
    },
    {
        title: 'Databases',
        href: database.index().url,
        icon: null,
    },
];

export function AppSidebar() {
    const { companies } = usePage<SharedData>().props;

    const teams = companies.reduce(
        (acc, company) => {
            acc[company.id] = {
                name: company.name,
                id: company.id,
            };
            return acc;
        },
        {} as Record<string, { name: string; id: number }>,
    );

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarGroupLabel>Company</SidebarGroupLabel>
                        <TeamSwitcher teams={teams} />
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <AppearanceTabs showLabel={false} className="w-full flex-wrap justify-center" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
