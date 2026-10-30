
<table class="table table-sm table-bordered text-center align-middle mb-0">
    <thead class="alert-info">
        <tr>
            <th>Name</th>
            <th>Relationship</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Occupation</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $fm): ?>
        <tr>
            <td><?= htmlspecialchars(trim(($fm['lname'] ?? '') . ', ' . ($fm['fname'] ?? '') . ' ' . ($fm['mi'] ?? ''))) ?></td>
            <td><?= htmlspecialchars($fm['relationship'] ?? '—') ?></td>
            <td><?= htmlspecialchars(isset($fm['age']) && $fm['age'] !== null ? $fm['age'] : '—') ?></td>
            <td><?= htmlspecialchars(($fm['sex'] ?? '') ?: '—') ?></td>
            <td><?= htmlspecialchars(($fm['occupation'] ?? '') ?: '—') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
