<x-app-layout>
    <div>
        <div>
            Todo List
            <div>
                <select>
                <option value="all">All tasks</option>
                <option value="pending">pending</option>
                <option value="completed">completed</option>

            </select>
            </div>

            <div>
                <input type="text" id="todoInput">
                <input type="date" id="todoDate">
                <select>
                    <option>Low Priority</option>
                    <option>Medium Priority </option>
                    <option>High Priority</option>
                </select>

                <button>Add task</button>
            </div>

            <div>
                <table~>
                    <thead>
                        <tr>
                            <th>
                                TASK
                            </th>
                            <th>
                                DUE DATE
                            </th> 
                            <th>
                                PRIORITY
                            </th>
                             <th>
                                STATUS
                            </th>
                             <th>
                                ACTIONS 
                                <!-- (i COULD ADD EDIT/DELETE OPTION) -->
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    
    </div>
</x-app-layout>