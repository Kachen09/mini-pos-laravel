<!DOCTYPE html>
<html lang="th">
<head>
    <title>จัดการข้อมูลลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> {{-- หรือ CSS อื่นๆ --}}
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-users me-2"></i> จัดการข้อมูลลูกค้า</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> เพิ่มลูกค้าใหม่
            </a>
        </div>

        {{-- แสดงข้อความ Success หรือ Error --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($message = Session::get('error'))
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>ชื่อ</th>
                            <th>อีเมล</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info btn-sm" title="ดูรายละเอียด"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm" title="แก้ไข"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบลูกค้ารายนี้?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="ลบ"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">ยังไม่มีข้อมูลลูกค้า</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- แสดง Pagination Links --}}
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">กลับหน้าหลัก</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>