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
import application from '@/routes/application';
import company from '@/routes/company';
import database from '@/routes/database';
import project from '@/routes/project';
import { SharedData } from '@/types';
import { type NavItem } from '@/types/resources';
import { Link, usePage } from '@inertiajs/react';
import { LayoutGrid, Building, Presentation, Database, AppWindowMac, ScreenShare, Server, Table, Columns2 } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import screen from '@/routes/screen';
import endpoint from '@/routes/endpoint';
import table from '@/routes/table';
import column from '@/routes/column';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Companies',
        href: company.index().url,
        icon: Building,
    },
    {
        title: 'separator',
        href: '#',
    },
    {
        title: 'Projects',
        href: project.index().url,
        icon: Presentation,
    },
    {
        title: 'Applications',
        href: application.index().url,
        icon: AppWindowMac,
    },
    {
        title: 'Screen',
        href: screen.index().url,
        icon: ScreenShare
    },
    {
        title: 'Endpoint',
        href: endpoint.index().url,
        icon: Server
    },
    {
        title: 'separator',
        href: '#',
    },
    {
        title: 'Databases',
        href: database.index().url,
        icon: Database,
    },
    {
        title: 'Tables',
        href: table.index().url,
        icon: Table
    },
    {
        title: 'Columns',
        href: column.index().url,
        icon: Columns2
    }
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
