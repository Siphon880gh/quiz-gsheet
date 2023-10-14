quiz2.php

Next you have the ability of converting the array of arrays into csv for importing into Google Sheet to make more permanent. 
Btw questions.questions is the array of arrays (representing the csv lines, and atomically the csv values)
```
function arrayToCSV(data) {
    // Transform each inner array into a CSV string, escape quotes and newlines
    const csvRows = data.map(row => 
        row.map(item => {
            // Convert the item to a string if it's not already
            const stringItem = String(item);

            // Escape double quotes and newlines
            let escapedItem = stringItem.replace(/"/g, '""').replace(/\n/g, '\\n');

            // If item contains a comma, newline, or double quote, enclose it in double quotes
            if (escapedItem.includes(',') || escapedItem.includes('\n') || escapedItem.includes('"')) {
                escapedItem = `"${escapedItem}"`;
            }

            return escapedItem;
        }).join(',')
    );

    // Combine all rows into a single CSV string
    const csvText = csvRows.join('\n');

    return csvText;
}

var quizData = [
    // ... (your data)
];

const csvContent = arrayToCSV(quizData);
console.log(csvContent);
```

If the code snippet needs to be readjusted:
https://chat.openai.com/c/02a0bba3-ee3f-4280-9846-71a620811f14