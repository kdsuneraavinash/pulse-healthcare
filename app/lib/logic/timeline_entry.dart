import 'package:pulse_healthcare/logic/dummy_data.dart';

class TimelineEntry {
  static List<TimelineEntry> _dummyTimeline;
  final DateTime _date;
  final String _doctorName;
  final bool _isImportant;
  final bool _hasReport;
  final int _medications;

  TimelineEntry({date, doctorName, isImportant, hasReport, medications})
      : this._date = date,
        this._doctorName = doctorName,
        this._isImportant = isImportant,
        this._hasReport = hasReport,
        this._medications = medications;

  factory TimelineEntry.loadFromMap(Map<String, dynamic> entry) {
    String strDate = entry['date'];
    List splittedDate = strDate.split('-');
    int year = int.parse(splittedDate[0]);
    int month = int.parse(splittedDate[1]);
    int date = int.parse(splittedDate[2]);

    return TimelineEntry(
      date: DateTime(year, month, date),
      doctorName: entry['doctor'],
      hasReport: entry['hasReport'],
      isImportant: entry['isImportant'],
      medications: entry['medications'],
    );
  }

  static List<TimelineEntry> getTimeline() {
    if (_dummyTimeline == null) {
      _dummyTimeline = [];
      for (Map entry in DummyTimeline.dummyTimeLine) {
        _dummyTimeline.add(TimelineEntry.loadFromMap(entry));
      }
      _dummyTimeline.sort((a, b) => b.date.compareTo(a.date));
    }
    return _dummyTimeline;
  }

  DateTime get date => this._date;
  String get doctorName => this._doctorName;
  bool get isImportant => this._isImportant;
  bool get hasReport => this._hasReport;
  int get medications => this._medications;
}
