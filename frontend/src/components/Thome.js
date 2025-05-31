import React, { useEffect, useState } from 'react'
import AuthUser from './AuthUser';
import axios from 'axios';
import moment from 'moment';





function Thome() {

    const { http, token, user } = AuthUser();

    console.log(user);
    console.log(http);


   const curentDate = moment().format('DD-MM-YYYY');
    console.log(curentDate);



    const [habit, setHabit] = useState("")
    const [bool, setBool] = useState("false")
    const [getHabs, seGetHabs] = useState([])    //stores the fetched habits from backend



    const handleHabitChange = (e) => {
        setHabit(e.target.value)
    }
    const handleBoolChange = (e) => {

        setBool(e.target.checked ? "turu" : "false")
        console.log(bool)
    }




    const handleSubmit = (e) => {
        e.preventDefault();
        const habitData = {
            email: user.email,
            habitName: habit,
            date: "12-12-2025",
            completed: bool
        }
        console.log(habitData)
        console.log(bool)

        http.post("/habits",
            { habit: habitData }
        )
            .then(response => {
                fetchHabits();

                console.log(response.data);
            })
            .catch(error => {
                console.log(error.response);
            })
    }


    useEffect(() => {
        fetchHabits();
    }, []);
    useEffect(() => {
        console.log('Boolean value:', bool); // Log the boolean value whenever it changes
    }, [bool]);

    const fetchHabits = () => {/// Need to make this dynamic. Now this only gives userid 2 data. if anoither logged in it will not give anything
        // http.get(`/users/${user.id}/habits`).then(response => {
        http.get(`/users/${user.id}/habits`).then(response => {

            console.log(response.data);
            console.log("USERID :" + user.id);

            // alert(response.data)
            seGetHabs(response.data)
        })
            .catch(error => {
                console.log("Error fetching data:", error);
            })
    }

    const handleDelete = (habitId) => {
        console.log(habitId);
        http.delete(`/habits/${habitId}`).then(response => {
            console.log(response.data);
            fetchHabits();
        })
            .catch(error => {
                console.log("Error deleting habit:", error);
            })
    }

    return (
        <div>

            <div>
                <form onSubmit={handleSubmit}>
                    <input placeholder="habit" onChange={handleHabitChange} />
                    <input placeholder='date' />
                    <input type='checkbox' onChange={handleBoolChange} />
                    <button>Submit</button>
                </form>


            </div>
            <div>
                <h1> List of habits Habits</h1>
            {getHabs.map(item => (
                    <div>

                        <h2 key={item.tableHabit_id}>{item.tableHabit_id} Habit:{item.habitName}</h2>
                        <button>Edit</button>  
                        <button onClick={() => handleDelete(item.tableHabit_id)}>Delete</button>

                        <table>
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th key={item.tableHabit_id}>{item.habitName}</th>
                                <th>Completed</th>
                            </tr>
                        
                            </thead>
                            <tbody>
                            {/* {item.logs.map(log => ( */}
                            <tr>
                               
                                <td key={item.habitLogs_id}>{item.habitLogs_id} {item.date}</td>
                                <td key={item.habitLogs_id}> {item.completed}</td>
                                <td>idk</td>




                            </tr>
                  {/*      ))} */}
                            </tbody>
                      

                        </table>


                    </div>

                ))} 
            </div>

        </div>
    )
}

export default Thome