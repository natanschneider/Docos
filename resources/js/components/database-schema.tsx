import {
    DatabaseSchemaNode,
    DatabaseSchemaNodeBody,
    DatabaseSchemaNodeHeader,
    DatabaseSchemaTableCell,
    DatabaseSchemaTableRow,
} from '@/components/database-schema-node';
import { LabeledHandle } from '@/components/labeled-handle';
import { tableModel } from '@/types/resources';
import { Position, Background, Edge, ReactFlow } from '@xyflow/react';
import { memo } from 'react';
import '@xyflow/react/dist/style.css';

export type DatabaseSchemaNodeData = {
    data: {
        label: string;
        schema: { title: string; type: string }[];
    };
};

export const DatabaseSchema = memo(({ data }: DatabaseSchemaNodeData) => {
    return (
        <DatabaseSchemaNode className="p-0">
            <DatabaseSchemaNodeHeader>{data.label}</DatabaseSchemaNodeHeader>
            <DatabaseSchemaNodeBody>
                {data.schema.map((entry) => (
                    <DatabaseSchemaTableRow key={entry.title}>
                        <DatabaseSchemaTableCell className="pr-6 pl-0 font-light">
                            <LabeledHandle id={entry.title} title={entry.title} type="target" position={Position.Left} />
                        </DatabaseSchemaTableCell>
                        <DatabaseSchemaTableCell className="pr-0 font-thin">
                            <LabeledHandle
                                id={entry.title}
                                title={entry.type}
                                type="source"
                                position={Position.Right}
                                className="p-0"
                                handleClassName="p-0"
                                labelClassName="p-0 w-full pr-3 text-right"
                            />
                        </DatabaseSchemaTableCell>
                    </DatabaseSchemaTableRow>
                ))}
            </DatabaseSchemaNodeBody>
        </DatabaseSchemaNode>
    );
});

const nodeTypes = {
    databaseSchema: DatabaseSchema,
};

export default function Diagram({ tables }: { tables: tableModel[] }) {
    const defaultNodes = [];
    const defaultEdges: Edge[] = [];

    for (const table of tables) {
        defaultNodes.push({
            id: table.id.toString(),
            type: 'databaseSchema',
            data: {
                label: table.name,
                schema: table?.columns?.map((column) => ({
                    title: column.name,
                    type: column.type?.name,
                })),
            },
            position: { x: 0, y: 0 },
        });

        if (table?.columns) {
            for (const col of table.columns) {
                if (col?.related_fks) {
                    for (const fk of col.related_fks) {
                        defaultEdges.push({
                            id: `${col.id}-${fk.id}`,
                            source: table.id.toString(),
                            target: fk.table_id.toString(),
                            sourceHandle: col.name,
                            targetHandle: fk.name
                        });
                    }
                }
            }
        }
    }

    return (
        <div className='w-5xl h-[40rem]'>
            <ReactFlow
                defaultNodes={defaultNodes}
                defaultEdges={defaultEdges}
                nodeTypes={nodeTypes}
                fitView
            >
                <Background />
            </ReactFlow>
        </div>
    )
}
