{{-- Subject rows in the office transcript format: paired subjects show each paper, then the combined marks; Total and Grade/GP span the group. --}}
@foreach($rows as $row)
    @php
        $failed = ($row['status'] ?? '') === 'Fail';
        $label = !empty($optionalLabel) ? ' '.$optionalLabel : '';
    @endphp
    @if($row['paired'] && count($row['papers']) > 1)
        @foreach($row['papers'] as $paperIndex => $paper)
            <tr class="tr-paper {{ $paperIndex === 0 ? 'tr-group-start' : '' }}">
                <td class="tr-subject">{{ $paper['name'] }}{{ $label }}</td>
                <td>{{ $paper['cq'] }}</td><td>{{ $paper['mcq'] }}</td><td>{{ $paper['practical'] }}</td>
                @if($paperIndex === 0)
                    <td class="tr-total tr-group-end" rowspan="{{ count($row['papers']) + 1 }}">{{ $row['combined']['total'] }}</td>
                    <td class="tr-grade tr-group-end" rowspan="{{ count($row['papers']) + 1 }}"><strong class="{{ $failed ? 'tr-fail' : '' }}">{{ $row['combined']['grade'] }}</strong><span>GP {{ $row['combined']['gp'] }}</span></td>
                @endif
            </tr>
        @endforeach
        <tr class="tr-combined tr-group-end">
            <td class="tr-subject tr-combined-label" aria-label="Paper marks added together"></td>
            <td><strong>{{ $row['combined']['cq'] }}</strong></td><td><strong>{{ $row['combined']['mcq'] }}</strong></td><td><strong>{{ $row['combined']['practical'] }}</strong></td>
        </tr>
    @else
        <tr>
            <td class="tr-subject">{{ $row['name'] }}{{ $label }}</td>
            <td>{{ $row['cq'] }}</td><td>{{ $row['mcq'] }}</td><td>{{ $row['practical'] }}</td>
            <td class="tr-total">{{ $row['total'] }}</td>
            <td class="tr-grade"><strong class="{{ $failed ? 'tr-fail' : '' }}">{{ $row['grade'] }}</strong><span>GP {{ $row['gradePoint'] }}</span></td>
        </tr>
    @endif
@endforeach
