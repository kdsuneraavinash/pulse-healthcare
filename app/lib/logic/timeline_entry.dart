class TimelineEntry {
  static List<TimelineEntry> _timeline;
  final DateTime _date;
  final String _doctorName;
  final List<MedicationEntry> _medications;

  TimelineEntry({date, doctorName, medications})
      : this._date = date,
        this._doctorName = doctorName,
        this._medications = medications;

  factory TimelineEntry.loadFromMap(Map<String, dynamic> entry) {
    String strDate = entry['date'];
    List splittedDate = strDate.split('/');
    int year = int.parse(splittedDate[2]);
    int month = int.parse(splittedDate[0]);
    int date = int.parse(splittedDate[1]);

    return TimelineEntry(
      date: DateTime(year, month, date),
      doctorName: entry['doctor'],
      medications: MedicationEntry.getMedications(
          List<Map<String, dynamic>>.from(entry['medications'])),
    );
  }

  static List<TimelineEntry> getTimeline(List<Map<String, dynamic>> data) {
    if (_timeline == null) {
      _timeline = [];

      for (Map entry in data) {
        _timeline.add(TimelineEntry.loadFromMap(entry));
      }
      _timeline.sort((a, b) => b.date.compareTo(a.date));
    }
    return _timeline;
  }

  DateTime get date => this._date;
  String get doctorName => this._doctorName;
  List<MedicationEntry> get medications => this._medications ?? [];
}

class MedicationEntry {
  final String _name;
  final String _dose;
  final String _time;
  final String _comment;

  MedicationEntry({name, dose, time, comment})
      : this._name = name,
        this._dose = dose,
        this._time = time,
        this._comment = comment;

  factory MedicationEntry.loadFromMap(Map<String, dynamic> entry) {
    return MedicationEntry(
      name: entry['name'],
      comment: entry['comment'],
      dose: entry['dose'],
      time: entry['time'],
    );
  }

  static List<MedicationEntry> getMedications(List<Map<String, dynamic>> data) {
    List<MedicationEntry> _medications = [];
    for (Map entry in data) {
      _medications.add(MedicationEntry.loadFromMap(entry));
    }
    return _medications;
  }

  // DateTime get date => this._date;
  String get name => this._name;
  String get dose => this._dose;
  String get comment => this._comment;
  String get time => this._time;
}
