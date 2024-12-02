const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {
    const oName = request.query.oName;

    if (!oName) {
        return response.status(400).json({ error: 'Organization name is required' });
    }

    const query = `
        SELECT 
            o.orgID,
            o.oName, 
            o.oDesc, 
            o.oLogo, 
            b.boothID, 
            b.location, 
            s.dayAvailable, 
            s.timeStart, 
            s.timeEnd
        FROM 
            BOOTHS b
        JOIN 
            SCHEDULES s ON b.boothID = s.boothID
        JOIN 
            ORGANIZATIONS o ON b.orgID = o.orgID
        WHERE 
            o.oName = ?;
    `;

    conn.query(query, [oName], (err, result) => {
        if (err) {
            console.error('Error executing query:', err);
            return response.status(500).json({ error: 'Failed to retrieve organizations' });
        }

        // Group results by organization and booth
        const groupedResults = [];
        result.forEach(row => {
            // Group by organization
            let org = groupedResults.find(org => org.oName === row.oName);

            if (!org) {
                // If organization doesn't exist in the groupedResults, create it
                org = {
                    orgID: row.orgID,
                    oName: row.oName,
                    oDesc: row.oDesc,
                    oLogo: row.oLogo ? `data:image/jpeg;base64,${row.oLogo.toString('base64')}` : null,
                    booths: []
                };
                groupedResults.push(org);
            }

            // Group by booth
            let booth = org.booths.find(b => b.boothID === row.boothID);
            if (!booth) {
                booth = {
                    boothID: row.boothID,
                    location: row.location,
                    schedules: []
                };
                org.booths.push(booth);
            }

            // Add the schedule for this booth
            booth.schedules.push({
                dayAvailable: row.dayAvailable,
                timeStart: row.timeStart,
                timeEnd: row.timeEnd
            });
        });

        // Return grouped results as JSON
        response.json(groupedResults);
    });
});

module.exports = router;
