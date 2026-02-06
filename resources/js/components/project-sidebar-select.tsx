import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { changeProject as changeProj } from '@/routes';
import { type SharedData } from '@/types';
import { projectModel } from '@/types/resources';
import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function ProjectSidebarSelect(projects: projectModel[]) {
    const { currentProject } = usePage<SharedData>().props;
    const [project, setProject] = useState<string>(currentProject?.toString() ?? projects[0]?.id?.toString());

    const changeProject = (value: string) => {
        setProject(value);
        router.flushAll();
        router.get(changeProj(value));
    };

    return (
        <div className="w-full justify-start my-4">
            <Label htmlFor="project">Project</Label>

            <input type="hidden" name="project" value={project} />
            <Select onValueChange={(value) => changeProject(value)} defaultValue={project}>
                <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Select a project" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Projects</SelectLabel>
                        {projects.map((project) => (
                            <SelectItem key={project.id} value={project.id.toString()}>
                                {project.name}
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    );
}
