import React, { useEffect, useRef, useState } from "react";
import Chart from "chart.js/auto";

export default function Dashboard({ auth }) {
    const [salesData, setSalesData] = useState(null);
    const [stats, setStats] = useState({});
    const [loadingSales, setLoadingSales] = useState(true);
    const [salesError, setSalesError] = useState(null);

    const [modalData, setModalData] = useState(null);
    const [modalLoading, setModalLoading] = useState(false);
    const [modalError, setModalError] = useState(null);

    const [period, setPeriod] = useState("monthly");
    const [selectedUser, setSelectedUser] = useState("");
    const [dateRange, setDateRange] = useState({ start: "", end: "" });
    const [showDatePicker, setShowDatePicker] = useState(false);
    const [visitStats, setVisitStats] = useState({});

    const salesCanvasRef = useRef(null);
    const salesChartInstance = useRef(null);
    const visitCanvasRef = useRef(null);
    const visitChartInstance = useRef(null);
    const trendCanvasRef = useRef(null);

    const isSuperadmin = Number(auth?.user?.role_id) === 1;
    const isSales = Number(auth?.user?.role_id) === 12;

    // Fetch Sales Performance
    useEffect(() => {
        fetchSalesPerformance();
    }, []);

    const fetchSalesPerformance = async () => {
        setLoadingSales(true);
        try {
            const res = await fetch("/api/sales-performance", {
                headers: { Accept: "application/json" },
                credentials: "same-origin",
            });
            const result = await res.json();
            if (result.success) {
                setSalesData(result);
                setStats(result.stats);
            } else {
                throw new Error(result.message || "Failed to load sales performance");
            }
        } catch (err) {
            setSalesError(err.message);
        } finally {
            setLoadingSales(false);
        }
    };

    // Sales Performance Chart Render
    useEffect(() => {
        if (!salesData || !salesCanvasRef.current) return;

        if (salesChartInstance.current) salesChartInstance.current.destroy();

        salesChartInstance.current = new Chart(salesCanvasRef.current, {
            type: "bar",
            data: {
                labels: salesData.data.labels,
                datasets: salesData.data.datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: "index", intersect: false },
                plugins: {
                    legend: { position: "bottom", labels: { padding: 15, font: { size: 12 } } },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const salesName = salesData.data.labels[index];
                        const salesDetails = salesData.details[index];
                        openSalesModal(salesDetails.user_id, salesName);
                    }
                },
            },
        });

        return () => salesChartInstance.current?.destroy();
    }, [salesData]);

    // Fetch & Render Visit Trend
    useEffect(() => {
        loadVisitTrend();
    }, [period, selectedUser]);

    const loadVisitTrend = async (startDate = null, endDate = null) => {
        if (!visitCanvasRef.current) return;

        let url = `/api/visit-trend?period=${period}`;
        if (startDate && endDate) url += `&start_date=${startDate}&end_date=${endDate}`;
        if (selectedUser) url += `&user_id=${selectedUser}`;
        else if (!isSuperadmin) url += `&user_id=${auth?.user?.id}`;

        try {
            const res = await fetch(url);
            const result = await res.json();
            if (result.success) {
                renderVisitTrendChart(result.data);
                setVisitStats(result.stats);
            }
        } catch (err) {
            console.error("Visit trend error", err);
        }
    };

    const renderVisitTrendChart = (data) => {
        if (visitChartInstance.current) visitChartInstance.current.destroy();

        const color = isSales ? "rgba(34,197,94,1)" : "rgba(59,130,246,1)";
        const fillTop = isSales ? "rgba(34,197,94,0.28)" : "rgba(59,130,246,0.28)";

        visitChartInstance.current = new Chart(visitCanvasRef.current, {
            type: "line",
            data: {
                labels: data.labels || [],
                datasets: [
                    {
                        label: "Kunjungan",
                        data: data.visits || [],
                        borderColor: color,
                        backgroundColor: (ctx) => {
                            const c = ctx.chart.ctx;
                            const g = c.createLinearGradient(0, 0, 0, 400);
                            g.addColorStop(0, fillTop);
                            g.addColorStop(1, "rgba(0,0,0,0.03)");
                            return g;
                        },
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: color,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                },
            },
        });
    };

    // Render Backward Compatibility Chart
    useEffect(() => {
        if (!trendCanvasRef.current) return;
        const legacyChart = new Chart(trendCanvasRef.current, {
            type: "line",
            data: {
                labels: ["Apr", "Mei", "Jun", "Jul", "Agu", "Sep"],
                datasets: [
                    {
                        label: "Proposal",
                        data: [50, 44, 52, 40, 60, 62],
                        borderColor: "rgba(34, 197, 94, 1)",
                        backgroundColor: "rgba(34, 197, 94, 0.2)",
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: "Customer",
                        data: [300, 350, 420, 450, 500, 490],
                        borderColor: "rgba(59, 130, 246, 1)",
                        backgroundColor: "rgba(59, 130, 246, 0.2)",
                        tension: 0.3,
                        fill: true,
                    },
                ],
            },
            options: { responsive: true, maintainAspectRatio: false },
        });
        return () => legacyChart.destroy();
    }, []);

    // Open Modal
    const openSalesModal = async (userId, name) => {
        setModalLoading(true);
        setModalError(null);
        setModalData({ name });
        try {
            const res = await fetch(`/api/sales-performance/${userId}`, {
                headers: { Accept: "application/json" },
                credentials: "same-origin",
            });
            const result = await res.json();
            if (result.success) {
                setModalData((prev) => ({ ...prev, ...result.company_visited }));
            } else {
                throw new Error(result.message || "Failed to load detail");
            }
        } catch (err) {
            setModalError(err.message);
        } finally {
            setModalLoading(false);
        }
    };

    return (
        <div className="p-6 space-y-6">
            {/* Sales Performance Stats */}
            <div className="grid grid-cols-3 gap-4">
                <div className="p-4 bg-white shadow rounded">Companies Visited: {stats.total_company_visited || 0}</div>
                <div className="p-4 bg-white shadow rounded">Total Deals: {stats.total_deal || 0}</div>
                <div className="p-4 bg-white shadow rounded">Total Fails: {stats.total_fails || 0}</div>
            </div>

            {/* Sales Performance Canvas */}
            <div className="relative h-96 bg-white p-4 shadow rounded">
                {loadingSales && <div className="absolute inset-0 flex items-center justify-center bg-white/70">Loading...</div>}
                {salesError ? (
                    <div className="text-red-500 text-center py-10">
                        <p>{salesError}</p>
                        <button onClick={fetchSalesPerformance} className="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Reload</button>
                    </div>
                ) : (
                    <canvas ref={salesCanvasRef} />
                )}
            </div>

            {/* Visit Trend Controls */}
            <div className="bg-white p-4 shadow rounded space-y-4">
                <div className="flex justify-between items-center">
                    <div className="flex gap-2">
                        {["daily", "weekly", "monthly", "yearly"].map((p) => (
                            <button
                                key={p}
                                onClick={() => { setPeriod(p); setShowDatePicker(false); }}
                                className={`px-3 py-1 rounded capitalize ${period === p && !showDatePicker ? "bg-blue-500 text-white" : "bg-gray-200 text-gray-700"}`}
                            >
                                {p}
                            </button>
                        ))}
                        <button
                            onClick={() => setShowDatePicker(!showDatePicker)}
                            className="px-3 py-1 bg-gray-200 text-gray-700 rounded"
                        >
                            Custom Range
                        </button>
                    </div>

                    {isSuperadmin && (
                        <select value={selectedUser} onChange={(e) => setSelectedUser(e.target.value)} className="border rounded p-1">
                            <option value="">All Sales</option>
                            {/* Map your superadmin options dynamically if supplied */}
                        </select>
                    )}
                </div>

                {showDatePicker && (
                    <div className="flex gap-2 items-center">
                        <input type="date" value={dateRange.start} onChange={(e) => setDateRange({ ...dateRange, start: e.target.value })} className="border rounded p-1" />
                        <input type="date" value={dateRange.end} onChange={(e) => setDateRange({ ...dateRange, end: e.target.value })} className="border rounded p-1" />
                        <button onClick={() => loadVisitTrend(dateRange.start, dateRange.end)} className="px-3 py-1 bg-blue-500 text-white rounded">Apply</button>
                    </div>
                )}

                <div className="h-80 relative">
                    <canvas ref={visitCanvasRef} />
                </div>
            </div>

            {/* Legacy Trend Chart */}
            <div className="h-64 bg-white p-4 shadow rounded">
                <canvas ref={trendCanvasRef} />
            </div>

            {/* Detail Modal */}
            {modalData && (
                <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50" onClick={() => setModalData(null)}>
                    <div className="bg-white rounded-lg p-6 max-w-2xl w-full" onClick={(e) => e.stopPropagation()}>
                        <h3 className="text-xl font-bold">{modalData.name} - Details</h3>
                        {modalLoading ? (
                            <p className="py-4">Loading stats...</p>
                        ) : modalError ? (
                            <p className="text-red-500 py-4">{modalError}</p>
                        ) : (
                            <div className="mt-4 space-y-2">
                                <p>Total Visited: {modalData.total || 0}</p>
                                <p>Deals: {modalData.deal || 0}</p>
                                <p>Fails: {modalData.fails || 0}</p>
                            </div>
                        )}
                        <button onClick={() => setModalData(null)} className="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Close</button>
                    </div>
                </div>
            )}
        </div>
    );
}